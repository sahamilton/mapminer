use mapminer;
select locations.id,locations.street, locations.city, locations.state,branches.id, branchname,branches.city,branches.state,
distance
from locations
left join branches 
select( 
    
                   3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat))) as distance
                  where locations.company_id = 290
                  )
                 
         on
         distance < 25
                   
                  
where locations.company_id = 290
order by locations.id, distance