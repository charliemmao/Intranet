<html>
<tr><th class=td1>Query Example</th>
    <td><select name="query_example">
		 <option>ALTER TABLE oldname RENAME TO newname;
        <option>ALTER TABLE alterdemo RENAME COLUMN col1 TO democol; 
        <option>ALTER TABLE alterdemo ADD COLUMN col2 INTEGER;
        <option>ALTER TABLE alterdemo ALTER COLUMN col2 SET DEFAULT 0;
        <option>ALTER TABLE alterdemo ALTER COLUMN col2 DROP DEFAULT;
        <option>CREATE TABLE child_test (col2 INTEGER) INHERITS (parent_test);
        <option>CREATE VIEW customer_ohio AS SELECT * FROM customer WHERE state = 'OH';
        <option>GRANT SELECT ON permtest TO meyers;
        <option>GRANT ALL ON permtest TO PUBLIC;
        <option>
        <option>
    </option></select></th>
</tr>

</html>
