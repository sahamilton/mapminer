<!-- Google Tag Manager -->
<script>

	window.dataLayer = window.dataLayer || [];
  	window.dataLayer.push({
  		'userId' : '{{{auth()->id()}}}',
		'employee_id' : '{{auth()->user() ? auth()->user()->employee_id : ''}}'

});
</script>


<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PZM3WV');</script>

<!-- End Google Tag Manager -->