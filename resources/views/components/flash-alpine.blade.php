<div id="flash" x-data="{...flashing(), show: false, message: '', timeout: null }" @flash.window="
        paint($event.detail.color),        
        message = $event.detail.message,
        show = true,
        clearTimeout(timeout),
        timeout = setTimeout(() => {show = false}, 3000)
        " x-show.transition.opacity.duration.300ms="show" x-on:click="show = false" x-text="message"
        style="display: none;">
</div>