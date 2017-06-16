<?php

return [

'owned_limit'=> env("OWNED_LEADS_LIMIT",5),
'search_radius'=>env("LEADS_SEARCH_RADIUS",100),
'lead_distribution_roles'=>(['Branch Managers','Sales']);

];