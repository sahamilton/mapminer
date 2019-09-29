update addresses, leadsource, address_branch
left join activities on address_branch.address_id = activities.address_id
left join opportunities on address_branch.id = opportunities.address_branch_id
set address_branch.branch_id = 9002
where address_branch.address_id = addresses.id
and addresses.lead_source_id = leadsource.id
and leadsource.source like "Hoover%" 
and address_branch.branch_id in 
(1455,1456,1661,1662,1665,1668,1669,1676,1677,1678,1679,1684,1686,1687,1689,1691,1693,1708,2666,2684,2685,2686,3062,3063,3064,3401,3404,3411,3413,3415,3416,3417,3426,3434,7254,7261,7262,8058)
and activities.id is null
and opportunities.id is null;

update addresses, leadsource, address_branch
left join activities on address_branch.address_id = activities.address_id
left join opportunities on address_branch.id = opportunities.address_branch_id
set address_branch.branch_id = 9001
where address_branch.address_id = addresses.id
and addresses.lead_source_id = leadsource.id
and leadsource.source like "Hoover%" 
and address_branch.branch_id in 
(
    1115,1149,1150,1151,1152,1153,1159,1160,1161,1605,1610,1611,1873,1874,1875,1878,1879,2254,2255,2256,2681,2700,2702,2751,2752,2753,2961,3300,3400,3403,3418,3422,3424,3430,3431,3433,7386,8027,8038
)
and activities.id is null
and opportunities.id is null;