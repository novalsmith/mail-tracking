UPDATE positionsclasification
SET 
createdBy = 'System', createdDate = UTC_TIMESTAMP(),
updatedBy = 'System', updatedDate = UTC_TIMESTAMP(),
STATUS = 'ACTIVE'

UPDATE employee SET es4 = 'SDB.K.3'
WHERE es4 LIKE '%Sub Kelompok Kerja A%'

SELECT distinct placement FROM employee

 SELECT a.name,c.oraganizationUnit,b.name as employeeName FROM masterdatalookup AS a
 INNER JOIN employee AS b ON a.code = b.roleCode 
 INNER JOIN positionsclasification AS c ON a.code = c.code
 WHERE a.type = 'ROLE'
 GROUP BY a.name,c.oraganizationUnit,b.name 
 
 DELETE FROM tracking
 -- insert into select
 
 SELECT MD5('admin'); 

INSERT INTO access
(employeeId,PASSWORD,STATUS,createdBy,createdDate,updatedBy,updatedDate)
SELECT
  em.employeeId,
  MD5(em.employeeId),
  'ACTIVE',
  'System',
  NOW(),
 'System',
  NOW()
FROM employee AS em

select `a`.`employeeId` AS `employeeId`,a.name,
`a`.`parent` AS `parent`,`a`.`roleCode` AS `roleCode` 
from (`employee` `a` join `v_masterdatalookup` `c` on(`c`.`code` = `a`.`roleCode` and
 `c`.`type` = 'ROLE')   

 )   
  -- join `positionsclasification` `b` on(`b`.`code` = `a`.`roleCode`)
WHERE a.employeeId = '196801011990032001'



SELECT distinct `a`.`employeeId` AS `employeeId`,`a`.`name` AS `name`,`d`.`name` AS `placement`,`a`.`position` AS `position`,`a`.`parent` AS `parent`,`a`.`roleCode` AS `roleCode`,
`c`.`name` AS `roleName`,concat(`a`.`roleCode`,' - ',`c`.`name`) AS `unitRoleName`,`e`.`oraganizationUnit` AS `es2`,`f`.`oraganizationUnit` AS `es3`,
`g`.`oraganizationUnit` AS `es4`,`b`.`unitCodeOrg` AS `unitCode` 
from ((((((`employee` `a` join `v_masterdatalookup` `c` on(`c`.`code` = `a`.`roleCode` and `c`.`type` = 'ROLE')) 
join `positionsclasification` `b` on(`b`.`code` = `a`.`roleCode`)) 
left join `positionsclasification` `e` on(`e`.`code` = `a`.`es2`)) 
left join `positionsclasification` `f` on(`f`.`code` = `a`.`es3`)) 
left join `positionsclasification` `g` on(`g`.`code` = `a`.`es4`)) 
left join `v_masterdatalookup` `d` on(`a`.`placement` = `d`.`code` and `d`.`type` = 'PLACE'))
ORDER BY a.name
 
 
select distinct `a`.`employeeId` AS `employeeId`,`a`.`name` AS `name`,
`a`.`position` AS `position`,`a`.`parent` AS `parent`,`a`.`roleCode` AS `roleCode`,`c`.`name` AS `roleName`,
concat(`a`.`roleCode`,' - ',`c`.`name`) AS `unitRoleName`,`a`.`es2`,`a`.`es3`,
`a`.`es4`,`b`.`unitCodeOrg` AS `unitCode`,`a`.`level` AS `level` 
from (((((`employee` `a` join `v_masterdatalookup` `c` on(`c`.`code` = `a`.`roleCode` and `c`.`type` = 'ROLE')) 
join `positionsclasification` `b` on(`b`.`code` = `a`.`roleCode`)) left join `positionsclasification` `e` on(`e`.`code` = `a`.`es2`)) 
left join `positionsclasification` `f` on(`f`.`code` = `a`.`es3`)) left join `positionsclasification` `g` on(`g`.`code` = `a`.`es4`)) 



INSERT into access
(accessId,employeeId, PASSWORD, STATUS, createdBy,createdDate,updatedBy,updatedDate)
SELECT
NULL,
  		a.employeeId,
	 MD5(a.employeeId),
	  'ACTIVE',
	'system',
	 NOW(),
	 'stystem',
	 NOW()
	FROM
employee AS a

SELECT * FROM employee

	UPDATE employee as em
	INNER JOIN positionsclasification p ON em.es1 = p.oraganizationUnit OR  em.es1 = p.positions
	SET es1 = p.code


	UPDATE employee as em
	INNER JOIN positionsclasification p ON em.es2 = p.oraganizationUnit OR  em.es2 = p.positions
	SET es2 = p.code


UPDATE employee as em
	INNER JOIN positionsclasification p ON em.es3 = p.oraganizationUnit OR  em.es3 = p.positions
	SET es3 = p.code
	
	UPDATE employee as em
	INNER JOIN positionsclasification p ON em.es4 = p.oraganizationUnit OR  em.es4 = p.positions
	SET es4 = p.code	
	
	
	
	
	select distinct 
    `a`.`employeeId` AS `employeeId`,
    `a`.`name` AS `name`,
    `a`.`position` AS `position`,
    `a`.`parent` AS `parent`,
    `a`.`roleCode` AS `roleCode`,
    `c`.`name` AS `roleName`,
concat(`a`.`roleCode`,' - ',`c`.`name`) AS `unitRoleName`,
case 
    when e.oraganizationUnit is null then e.positions ELSE e.oraganizationUnit
end AS `es2`,
case 
    when f.oraganizationUnit is null then f.positions  ELSE f.oraganizationUnit
end AS `es3`,
case 
    when g.oraganizationUnit is null then g.positions  ELSE g.oraganizationUnit
end AS `es4`,
`b`.`unitCodeOrg` AS `unitCode`,
`a`.`level` AS `level`,
a.`status` 
from (((((`employee` `a` join `v_masterdatalookup` `c` on(`c`.`code` = `a`.`roleCode` and `c`.`type` = 'ROLE')) 
join `positionsclasification` `b` on(`b`.`code` = `a`.`roleCode`)) 
left join `positionsclasification` `e` on(`e`.`code` = `a`.`es2`)) 
left join `positionsclasification` `f` on(`f`.`code` = `a`.`es3`)) 
left join `positionsclasification` `g` on(`g`.`code` = `a`.`es4`))


	
	select distinct 
    `a`.`employeeId` AS `employeeId`,
    `a`.`name` AS `name`,
    `a`.`position` AS `position`,
    `a`.`parent` AS `parent`,
    `a`.`roleCode` AS `roleCode`,
case 
    when e.oraganizationUnit is null then e.positions ELSE e.oraganizationUnit
end AS `es2`,
case 
    when f.oraganizationUnit is null then f.positions  ELSE f.oraganizationUnit
end AS `es3`,
case 
    when g.oraganizationUnit is null then g.positions  ELSE g.oraganizationUnit
end AS `es4`,
'' AS `unitCode`,
`a`.`level` AS `level` 
FROM employee AS a 
LEFT  join `positionsclasification` `e` on(`e`.`code` = `a`.`es2`)
LEFT  join `positionsclasification` `f` on(`f`.`code` = `a`.`es3`) 
LEFT  join `positionsclasification` `g` on(`g`.`code` = `a`.`es4`)
 
	
	SELECT * FROM employee WHERE employeeId = '196708301994031002'
	Pengadministrasi Umum - Wisnu Wardono
	SET es2 = p.code 
	WHERE es2 in (
	SELECT CODE from positionsclasification
	WHERE p.oraganizationUnit = es2 OR p.positions = es2
	) AS p


SELECT MD5('132')


SELECT aa.employeeId, aa.name FROM employee AS aa
INNER JOIN masterdatalookup AS bb ON aa.roleCode = bb.code AND TYPE = 'ROLE'
 -- WHERE employeeId = '196801011990032001'
 ORDER BY name
 
 SELECT * FROM masterdatalookup WHERE TYPE = 'ROLE' AND CODE = 'SDB.1.0'
 SELECT * FROM employee
  WHERE employeeId = '199109262015031006'


SELECT * FROM positionsclasification
WHERE CODE LIKE '%SDB%'

SELECT CONCAT('"%','das','%"')

DELIMITER //
delete FROM employee
WHERE LEVEL = 0 AND employeeId not IN(
'197009161994032001',
'199310052015032001',
'198412192009021001',
'199012122010011002'
)
SELECT COUNT(*) FROM v_employee



select distinct `a`.`employeeId` AS `employeeId`,`a`.`name` AS `name`,`a`.`position` AS `position`,
`a`.`parent` AS `parent`,`a`.`roleCode` AS `roleCode`,`c`.`name` AS `roleName`,
concat(`a`.`level`,' - ',`c`.`name`) AS `unitRoleName`,
case when `e`.`oraganizationUnit` is null then `e`.`positions` else `e`.`oraganizationUnit` end AS `es2`,
case when `f`.`oraganizationUnit` is null then `f`.`positions` else `f`.`oraganizationUnit` end AS `es3`,
case when `g`.`oraganizationUnit` is null then `g`.`positions` else `g`.`oraganizationUnit` end AS `es4`,
`a`.`level` AS `level`,`a`.`status` AS `status` 
from ((((`employee` `a` join `v_masterdatalookup` `c` on(`c`.`code` = `a`.`level` and `c`.`type` = 'LEVEL')) 
left join `positionsclasification` `e` on(`e`.`code` = `a`.`es2`)) 
left join `positionsclasification` `f` on(`f`.`code` = `a`.`es3`)) 
left join `positionsclasification` `g` on(`g`.`code` = `a`.`es4`))
GROUP BY 

`a`.`employeeId`,`a`.`name`,`a`.`position` ,
`a`.`parent`,`a`.`roleCode`,`c`.`name`  ,`a`.`level`  ,`a`.`status` 

CREATE PROCEDURE getTracking 
( 
   noSurat VARCHAR(100)
) 
BEGIN  
   SET @email = 'test@test.com';
SELECT [trackingtrackingnumber] from tracking WHERE [number] LIKE CONCAT('%', @email, '%');
END; //
DELIMITER //


DELETE FROM tracking




select `tracking`.`trackingid` AS `trackingid`,`tracking`.`agendaNumber` AS `agendaNumber`,
`tracking`.`receiptDate` AS `receiptDate`,`tracking`.`number` AS `number`,`tracking`.`realDate` AS `realDate`,
`tracking`.`type` AS `type`,`tracking`.`note` AS `note`,`tracking`.`from` AS `from`,`tracking`.`to` AS `to`,
`tracking`.`description` AS `description`,`tracking`.`sequence` AS `sequence`,`tracking`.`isDone` AS `isDone`,
`tracking`.`createdBy` AS `createdBy`,`tracking`.`createdDate` AS `createdDate`,`tracking`.`updatedBy` AS `updatedBy`,
`tracking`.`updatedDate` AS `updatedDate`,`tracking`.`isUnknown` AS `isUnknown`,
`tracking`.`takenBy` AS `takenBy`,`tracking`.`takenDate` AS `takenDate` 
from `tracking` as tr left join v_position as ps on tr.to = ps.code