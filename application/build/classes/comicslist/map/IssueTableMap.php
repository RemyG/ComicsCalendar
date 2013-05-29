<?php



/**
 * This class defines the structure of the 'comics_issue' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.comicslist.map
 */
class IssueTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'comicslist.map.IssueTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('comics_issue');
        $this->setPhpName('Issue');
        $this->setClassname('Issue');
        $this->setPackage('comicslist');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addForeignKey('serie_id', 'SerieId', 'INTEGER', 'comics_serie', 'id', true, null, null);
        $this->addColumn('pub_date', 'PubDate', 'DATE', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Serie', 'Serie', RelationMap::MANY_TO_ONE, array('serie_id' => 'id', ), null, null);
    } // buildRelations()

} // IssueTableMap
