@if(App::environment() =='production')
<script
    src="//d2s6cp23z9c3gz.cloudfront.net/js/embed.widget.min.js"
    data-domain="trueblue.besnappy.com"
    data-lang="en"
	data-name="{{ auth()->user() ? auth()->user()->fullName() : ''  }}"  
	data-email="{{ auth()->user() ? auth()->user()->email : '' }}"  >
</script>
@endif
