@wire
<x-form-input required name="address.businessname" label="Business name:" />
<x-form-input required name="address.street" label="Address:" />
<x-form-input required name="address.city" label="City:" />
<x-form-select required name="address.state" label="State:" :options="$states" />
<x-form-input required name="address.zip" label="ZIP/Postcode:" />
<x-form-input name="address.phone" label="Phone:" />
<x-form-input name="address.customer_id" label="Customer ID:" />
@endwire

