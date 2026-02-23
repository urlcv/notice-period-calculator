{{--
  Notice Period Calculator — fully client-side Alpine.js tool.
  No server round-trip. All date logic runs in the browser.
--}}
<div
    x-data="noticePeriodCalculator()"
    x-init="compute()"
    class="space-y-6"
>
    {{-- Tip --}}
    <div class="rounded-xl p-4 text-sm bg-blue-50 border border-blue-200 text-blue-800">
        <span class="font-semibold">Tip: </span>
        <span>Notice usually starts the day after you hand in your resignation, unless your contract says otherwise.</span>
    </div>

    {{-- Form --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="npc-start-date" class="block text-sm font-medium text-gray-700 mb-1">Start date</label>
            <input
                type="date"
                id="npc-start-date"
                x-model="startDate"
                @input="compute()"
                class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
        </div>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <div class="flex-1">
                <label for="npc-amount" class="block text-sm font-medium text-gray-700 mb-1">Notice period</label>
                <div class="flex rounded-lg border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-primary-500">
                    <input
                        type="number"
                        id="npc-amount"
                        min="1"
                        step="1"
                        x-model.number="amount"
                        @input="compute()"
                        placeholder="e.g. 4"
                        class="block w-full rounded-none border-0 px-3 py-2 text-sm focus:ring-0"
                    />
                    <select
                        x-model="unit"
                        @change="compute()"
                        class="border-0 border-l border-gray-300 bg-gray-50 pl-3 pr-10 py-2 text-sm text-gray-700 focus:ring-0 min-w-[7.5rem] appearance-none bg-no-repeat bg-[length:1rem_1rem] bg-[right_0.75rem_center]"
                        style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;);"
                    >
                        <option value="days">Days</option>
                        <option value="weeks">Weeks</option>
                        <option value="months">Months</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input
            type="checkbox"
            id="npc-working-days"
            x-model="workingDaysOnly"
            @change="compute()"
            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
        />
        <label for="npc-working-days" class="text-sm text-gray-700">Count working days only (exclude weekends)</label>
    </div>

    {{-- Result --}}
    <template x-if="result">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700">Last day of notice</h3>
            <div class="flex flex-wrap items-center gap-2">
                <p class="text-lg font-bold text-gray-900" x-text="result ? result.formattedDate : ''"></p>
                <button
                    type="button"
                    @click="copyResult()"
                    class="text-sm px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                    x-text="copyFeedback ? copyFeedback : 'Copy'"
                ></button>
            </div>
            <p class="text-sm text-gray-600" x-text="result ? result.summary : ''"></p>
            <template x-if="result && result.isWeekend">
                <div class="rounded-lg p-3 text-sm bg-amber-50 border border-amber-200 text-amber-800">
                    <span x-text="result ? result.weekendNote : ''"></span>
                </div>
            </template>
        </div>
    </template>

    {{-- Empty / invalid state --}}
    <template x-if="!result && startDate">
        <div class="text-center py-6 text-gray-500 text-sm rounded-xl border border-gray-200 bg-gray-50">
            Enter a valid notice period (positive number).
        </div>
    </template>

    <template x-if="!result && !startDate">
        <div class="text-center py-8 text-gray-400 text-sm">
            Enter your notice start date and notice period above to see your last day.
        </div>
    </template>
</div>

@push('scripts')
<script>
function noticePeriodCalculator() {
    const DAY_MS = 24 * 60 * 60 * 1000;
    const WEEKDAY_SAT = 6;
    const WEEKDAY_SUN = 0;

    return {
        startDate: '',
        amount: 4,
        unit: 'weeks',
        workingDaysOnly: false,
        result: null,
        copyFeedback: 'Copy',

        compute() {
            const start = this.startDate ? this.parseDate(this.startDate) : null;
            const num = typeof this.amount === 'number' ? this.amount : parseInt(this.amount, 10);
            if (!start || isNaN(num) || num < 1) {
                this.result = null;
                return;
            }

            let endDate;
            if (this.workingDaysOnly) {
                endDate = this.addWorkingDays(start, num);
            } else {
                if (this.unit === 'days') {
                    endDate = this.addDays(start, num);
                } else if (this.unit === 'weeks') {
                    endDate = this.addDays(start, num * 7);
                } else {
                    endDate = this.addMonths(start, num);
                }
            }

            const formatted = this.formatLong(endDate);
            const isWeekend = endDate.getDay() === WEEKDAY_SAT || endDate.getDay() === WEEKDAY_SUN;
            let weekendNote = '';
            if (isWeekend) {
                const dayName = endDate.getDay() === WEEKDAY_SAT ? 'Saturday' : 'Sunday';
                weekendNote = 'This date is a ' + dayName + '; your last working day may be the previous Friday.';
            }

            let summary = '';
            if (this.workingDaysOnly) {
                summary = num + ' working day' + (num !== 1 ? 's' : '') + ' from ' + this.formatShort(start);
            } else {
                if (this.unit === 'days') {
                    summary = num + ' calendar day' + (num !== 1 ? 's' : '') + ' from ' + this.formatShort(start);
                } else if (this.unit === 'weeks') {
                    summary = num + ' week' + (num !== 1 ? 's' : '') + ' from ' + this.formatShort(start);
                } else {
                    summary = num + ' month' + (num !== 1 ? 's' : '') + ' from ' + this.formatShort(start);
                }
            }

            this.result = {
                endDate,
                formattedDate: formatted,
                summary,
                isWeekend,
                weekendNote,
            };
        },

        parseDate(iso) {
            const d = new Date(iso + 'T12:00:00');
            return isNaN(d.getTime()) ? null : d;
        },

        addDays(start, n) {
            const t = start.getTime() + n * DAY_MS;
            return new Date(t);
        },

        addMonths(start, n) {
            const d = new Date(start);
            d.setMonth(d.getMonth() + n);
            return d;
        },

        addWorkingDays(start, n) {
            const d = new Date(start.getTime());
            let count = 0;
            while (count < n) {
                const w = d.getDay();
                if (w !== WEEKDAY_SAT && w !== WEEKDAY_SUN) count++;
                if (count < n) d.setTime(d.getTime() + DAY_MS);
            }
            return d;
        },

        formatLong(d) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            return days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
        },

        formatShort(d) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return d.getDate() + ' ' + months[d.getMonth()];
        },

        copyResult() {
            if (!this.result) return;
            const text = this.result.formattedDate;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    this.copyFeedback = 'Copied!';
                    setTimeout(() => { this.copyFeedback = 'Copy'; }, 2000);
                });
            }
        },
    };
}
</script>
@endpush
