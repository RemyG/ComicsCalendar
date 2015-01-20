<?php

namespace Map;

use \Issue;
use \IssueQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


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
 */
class IssueTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.IssueTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'comicslist';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'comics_issue';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Issue';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Issue';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'comics_issue.id';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'comics_issue.title';

    /**
     * the column name for the issue_number field
     */
    const COL_ISSUE_NUMBER = 'comics_issue.issue_number';

    /**
     * the column name for the serie_id field
     */
    const COL_SERIE_ID = 'comics_issue.serie_id';

    /**
     * the column name for the pub_date field
     */
    const COL_PUB_DATE = 'comics_issue.pub_date';

    /**
     * the column name for the cv_id field
     */
    const COL_CV_ID = 'comics_issue.cv_id';

    /**
     * the column name for the cv_url field
     */
    const COL_CV_URL = 'comics_issue.cv_url';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Title', 'IssueNumber', 'SerieId', 'PubDate', 'CvId', 'CvUrl', ),
        self::TYPE_CAMELNAME     => array('id', 'title', 'issueNumber', 'serieId', 'pubDate', 'cvId', 'cvUrl', ),
        self::TYPE_COLNAME       => array(IssueTableMap::COL_ID, IssueTableMap::COL_TITLE, IssueTableMap::COL_ISSUE_NUMBER, IssueTableMap::COL_SERIE_ID, IssueTableMap::COL_PUB_DATE, IssueTableMap::COL_CV_ID, IssueTableMap::COL_CV_URL, ),
        self::TYPE_FIELDNAME     => array('id', 'title', 'issue_number', 'serie_id', 'pub_date', 'cv_id', 'cv_url', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Title' => 1, 'IssueNumber' => 2, 'SerieId' => 3, 'PubDate' => 4, 'CvId' => 5, 'CvUrl' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'title' => 1, 'issueNumber' => 2, 'serieId' => 3, 'pubDate' => 4, 'cvId' => 5, 'cvUrl' => 6, ),
        self::TYPE_COLNAME       => array(IssueTableMap::COL_ID => 0, IssueTableMap::COL_TITLE => 1, IssueTableMap::COL_ISSUE_NUMBER => 2, IssueTableMap::COL_SERIE_ID => 3, IssueTableMap::COL_PUB_DATE => 4, IssueTableMap::COL_CV_ID => 5, IssueTableMap::COL_CV_URL => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'title' => 1, 'issue_number' => 2, 'serie_id' => 3, 'pub_date' => 4, 'cv_id' => 5, 'cv_url' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * Initialize the table attributes and columns
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
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Issue');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('issue_number', 'IssueNumber', 'VARCHAR', false, 10, null);
        $this->addForeignKey('serie_id', 'SerieId', 'INTEGER', 'comics_serie', 'id', true, null, null);
        $this->addColumn('pub_date', 'PubDate', 'DATE', true, null, null);
        $this->addColumn('cv_id', 'CvId', 'VARCHAR', false, 10, null);
        $this->addColumn('cv_url', 'CvUrl', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Serie', '\\Serie', RelationMap::MANY_TO_ONE, array('serie_id' => 'id', ), null, null);
        $this->addRelation('UserIssue', '\\UserIssue', RelationMap::ONE_TO_MANY, array('id' => 'issue_id', ), null, null, 'UserIssues');
        $this->addRelation('User', '\\User', RelationMap::MANY_TO_MANY, array(), null, null, 'Users');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? IssueTableMap::CLASS_DEFAULT : IssueTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Issue object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = IssueTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = IssueTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + IssueTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = IssueTableMap::OM_CLASS;
            /** @var Issue $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            IssueTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = IssueTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = IssueTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Issue $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                IssueTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(IssueTableMap::COL_ID);
            $criteria->addSelectColumn(IssueTableMap::COL_TITLE);
            $criteria->addSelectColumn(IssueTableMap::COL_ISSUE_NUMBER);
            $criteria->addSelectColumn(IssueTableMap::COL_SERIE_ID);
            $criteria->addSelectColumn(IssueTableMap::COL_PUB_DATE);
            $criteria->addSelectColumn(IssueTableMap::COL_CV_ID);
            $criteria->addSelectColumn(IssueTableMap::COL_CV_URL);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.issue_number');
            $criteria->addSelectColumn($alias . '.serie_id');
            $criteria->addSelectColumn($alias . '.pub_date');
            $criteria->addSelectColumn($alias . '.cv_id');
            $criteria->addSelectColumn($alias . '.cv_url');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(IssueTableMap::DATABASE_NAME)->getTable(IssueTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(IssueTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(IssueTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new IssueTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Issue or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Issue object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IssueTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Issue) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(IssueTableMap::DATABASE_NAME);
            $criteria->add(IssueTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = IssueQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            IssueTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                IssueTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the comics_issue table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return IssueQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Issue or Criteria object.
     *
     * @param mixed               $criteria Criteria or Issue object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IssueTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Issue object
        }

        if ($criteria->containsKey(IssueTableMap::COL_ID) && $criteria->keyContainsValue(IssueTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.IssueTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = IssueQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // IssueTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
IssueTableMap::buildTableMap();
