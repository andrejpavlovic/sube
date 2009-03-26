-- Move all test papers to handwritten notes category
UPDATE courseware
SET category = 3
WHERE category = 4;

-- Remove test papers category
DELETE FROM cw_category
WHERE category_name = 'Test Papers';
