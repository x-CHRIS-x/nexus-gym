-- Update members table
ALTER TABLE members DROP COLUMN full_name;
ALTER TABLE members
ADD COLUMN first_name varchar(50) NOT NULL AFTER id,
ADD COLUMN last_name varchar(50) NOT NULL AFTER first_name;

-- Update employees table
ALTER TABLE employees DROP COLUMN full_name;
ALTER TABLE employees
ADD COLUMN first_name varchar(50) NOT NULL AFTER id,
ADD COLUMN last_name varchar(50) NOT NULL AFTER first_name;

-- Update any existing triggers or views if needed