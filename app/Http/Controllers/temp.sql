select companies.companyname,businessname,locations.street, locations.city, locations.state,branches.id, branchname,branches.city,branches.state,
(3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat)))) as branchdistance
from companies,locations,branches
where companies.id = 144
and locations.company_id = companies.id  
and (3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat)))) < 100
order by locations.id,branchdistance