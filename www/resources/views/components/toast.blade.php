<div
    x-data="{
        show: false,
        type: 'success',
        message: '',
        timeout: null,
        showToast(msg, t = 'success') {
            this.message = msg
            this.type = t
            this.show = true
            clearTimeout(this.timeout)
            this.timeout = setTimeout(() => this.show = false, 3000)
            if (t === 'success') playSound('success')
            else if (t === 'error') playSound('error')
            else playSound('click')
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-5"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-5"
    x-cloak
    class="fixed bottom-6 right-6 z-50"
    @toast.window="showToast($event.detail.message, $event.detail.type)"
>
    <div
        class="px-4 py-3 rounded-lg text-white shadow-lg flex items-center gap-3"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-yellow-500': type === 'warning'
        }"
    >
        <span x-text="message"></span>
    </div>
</div>
