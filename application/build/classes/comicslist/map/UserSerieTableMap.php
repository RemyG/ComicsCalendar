<?php



/**
 * This class defines the structure of the 'comics_user_serie' table.
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
class UserSerieTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'comicslist.map.UserSerieTableMap';

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
        $this->setName('comics_user_serie');
        $this->setPhpName('UserSerie');
        $this->setClassname('UserSerie');
        $this->setPackage('comicslist');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('user_id', 'UserId', 'INTEGER' , 'comics_user', 'id', true, null, null);
        $this->addForeignPrimaryKey('serie_id', 'SerieId', 'INTEGER' , 'comics_serie', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
        $this->addRelation('Serie', 'Serie', RelationMap::MANY_TO_ONE, array('serie_id' => 'id', ), null, null);
    } // buildRelations()

} // UserSerieTableMap
