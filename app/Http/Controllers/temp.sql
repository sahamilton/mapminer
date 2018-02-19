select locations.*,branches.*,t1.branchdistance
from locations
left join 
       (select branchname,branches.id as branch,locations.id as location, 
              (3956 * acos(cos(radians(locations.lat)) 
                     * cos(radians(branches.lat)) 
                     * cos(radians(branches.lng) 
                     - radians(locations.lng)) 
                     + sin(radians(locations.lat)) 
                     * sin(radians(branches.lat)))) as branchdistance
       from branches,locations
       order by branchdistance
       limit 5) t1
on (3956 * acos(cos(radians(locations.lat)) 
       * cos(radians(t1.lat)) 
       * cos(radians(t1.lng) 
       - radians(locations.lng)) 
       + sin(radians(locations.lat)) 
       * sin(radians(t1.lat)))) < 25

where locations.company_id = 144 
order by locations.id, branchdistance


SELECT *
FROM tbl_product ta
JOIN (SELECT * FROM tbl_product tz WHERE tz.product_id = ta.product_id LIMIT 10) tc
LEFT JOIN (SELECT tx.transaction_date FROM tbl_transaction tx 
    WHERE tx.product_id=ta.product_id LIMIT 5) tb