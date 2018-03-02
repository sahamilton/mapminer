use mapminer;
select locations.id,locations.street, locations.city, locations.state,branches.id, branchname,branches.city,branches.state,
(3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat)))) as branchdistance
from locations
left join branches on 
    
                   3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat)))
                     < 25
                   
                  
where locations.company_id = 388
order by locations.id, branchdistance