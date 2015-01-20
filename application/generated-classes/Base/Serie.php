<?php

namespace Base;

use \Issue as ChildIssue;
use \IssueQuery as ChildIssueQuery;
use \Serie as ChildSerie;
use \SerieQuery as ChildSerieQuery;
use \User as ChildUser;
use \UserQuery as ChildUserQuery;
use \UserSerie as ChildUserSerie;
use \UserSerieQuery as ChildUserSerieQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\SerieTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'comics_serie' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Serie implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\SerieTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the cv_id field.
     * @var        string
     */
    protected $cv_id;

    /**
     * The value for the cv_url field.
     * @var        string
     */
    protected $cv_url;

    /**
     * The value for the added_on field.
     * Note: this column has a database default value of: NULL
     * @var        \DateTime
     */
    protected $added_on;

    /**
     * @var        ObjectCollection|ChildIssue[] Collection to store aggregation of ChildIssue objects.
     */
    protected $collIssues;
    protected $collIssuesPartial;

    /**
     * @var        ObjectCollection|ChildUserSerie[] Collection to store aggregation of ChildUserSerie objects.
     */
    protected $collUserSeries;
    protected $collUserSeriesPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildIssue[]
     */
    protected $issuesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserSerie[]
     */
    protected $userSeriesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->added_on = PropelDateTime::newInstance(NULL, null, 'DateTime');
    }

    /**
     * Initializes internal state of Base\Serie object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Serie</code> instance.  If
     * <code>obj</code> is an instance of <code>Serie</code>, delegates to
     * <code>equals(Serie)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Serie The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [cv_id] column value.
     *
     * @return string
     */
    public function getCvId()
    {
        return $this->cv_id;
    }

    /**
     * Get the [cv_url] column value.
     *
     * @return string
     */
    public function getCvUrl()
    {
        return $this->cv_url;
    }

    /**
     * Get the [optionally formatted] temporal [added_on] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getAddedOn($format = NULL)
    {
        if ($format === null) {
            return $this->added_on;
        } else {
            return $this->added_on instanceof \DateTime ? $this->added_on->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[SerieTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[SerieTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [cv_id] column.
     *
     * @param  string $v new value
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function setCvId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cv_id !== $v) {
            $this->cv_id = $v;
            $this->modifiedColumns[SerieTableMap::COL_CV_ID] = true;
        }

        return $this;
    } // setCvId()

    /**
     * Set the value of [cv_url] column.
     *
     * @param  string $v new value
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function setCvUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cv_url !== $v) {
            $this->cv_url = $v;
            $this->modifiedColumns[SerieTableMap::COL_CV_URL] = true;
        }

        return $this;
    } // setCvUrl()

    /**
     * Sets the value of [added_on] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function setAddedOn($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->added_on !== null || $dt !== null) {
            if ( ($dt != $this->added_on) // normalized values don't match
                || ($dt->format('Y-m-d H:i:s') === NULL) // or the entered value matches the default
                 ) {
                $this->added_on = $dt;
                $this->modifiedColumns[SerieTableMap::COL_ADDED_ON] = true;
            }
        } // if either are not null

        return $this;
    } // setAddedOn()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->added_on && $this->added_on->format('Y-m-d H:i:s') !== NULL) {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SerieTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SerieTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SerieTableMap::translateFieldName('CvId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cv_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SerieTableMap::translateFieldName('CvUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cv_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SerieTableMap::translateFieldName('AddedOn', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->added_on = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = SerieTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Serie'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SerieTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSerieQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collIssues = null;

            $this->collUserSeries = null;

            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Serie::setDeleted()
     * @see Serie::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SerieTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildSerieQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SerieTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SerieTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->usersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \UserSerieQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->usersScheduledForDeletion = null;
                }

            }

            if ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if (!$user->isDeleted() && ($user->isNew() || $user->isModified())) {
                        $user->save($con);
                    }
                }
            }


            if ($this->issuesScheduledForDeletion !== null) {
                if (!$this->issuesScheduledForDeletion->isEmpty()) {
                    \IssueQuery::create()
                        ->filterByPrimaryKeys($this->issuesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->issuesScheduledForDeletion = null;
                }
            }

            if ($this->collIssues !== null) {
                foreach ($this->collIssues as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->userSeriesScheduledForDeletion !== null) {
                if (!$this->userSeriesScheduledForDeletion->isEmpty()) {
                    \UserSerieQuery::create()
                        ->filterByPrimaryKeys($this->userSeriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userSeriesScheduledForDeletion = null;
                }
            }

            if ($this->collUserSeries !== null) {
                foreach ($this->collUserSeries as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[SerieTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SerieTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SerieTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(SerieTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(SerieTableMap::COL_CV_ID)) {
            $modifiedColumns[':p' . $index++]  = 'cv_id';
        }
        if ($this->isColumnModified(SerieTableMap::COL_CV_URL)) {
            $modifiedColumns[':p' . $index++]  = 'cv_url';
        }
        if ($this->isColumnModified(SerieTableMap::COL_ADDED_ON)) {
            $modifiedColumns[':p' . $index++]  = 'added_on';
        }

        $sql = sprintf(
            'INSERT INTO comics_serie (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'title':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'cv_id':
                        $stmt->bindValue($identifier, $this->cv_id, PDO::PARAM_STR);
                        break;
                    case 'cv_url':
                        $stmt->bindValue($identifier, $this->cv_url, PDO::PARAM_STR);
                        break;
                    case 'added_on':
                        $stmt->bindValue($identifier, $this->added_on ? $this->added_on->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SerieTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTitle();
                break;
            case 2:
                return $this->getCvId();
                break;
            case 3:
                return $this->getCvUrl();
                break;
            case 4:
                return $this->getAddedOn();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Serie'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Serie'][$this->hashCode()] = true;
        $keys = SerieTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getCvId(),
            $keys[3] => $this->getCvUrl(),
            $keys[4] => $this->getAddedOn(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[4]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[4]];
            $result[$keys[4]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collIssues) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'issues';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comics_issues';
                        break;
                    default:
                        $key = 'Issues';
                }

                $result[$key] = $this->collIssues->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserSeries) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userSeries';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comics_user_series';
                        break;
                    default:
                        $key = 'UserSeries';
                }

                $result[$key] = $this->collUserSeries->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Serie
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SerieTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Serie
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitle($value);
                break;
            case 2:
                $this->setCvId($value);
                break;
            case 3:
                $this->setCvUrl($value);
                break;
            case 4:
                $this->setAddedOn($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = SerieTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCvId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setCvUrl($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAddedOn($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Serie The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SerieTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SerieTableMap::COL_ID)) {
            $criteria->add(SerieTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(SerieTableMap::COL_TITLE)) {
            $criteria->add(SerieTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(SerieTableMap::COL_CV_ID)) {
            $criteria->add(SerieTableMap::COL_CV_ID, $this->cv_id);
        }
        if ($this->isColumnModified(SerieTableMap::COL_CV_URL)) {
            $criteria->add(SerieTableMap::COL_CV_URL, $this->cv_url);
        }
        if ($this->isColumnModified(SerieTableMap::COL_ADDED_ON)) {
            $criteria->add(SerieTableMap::COL_ADDED_ON, $this->added_on);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildSerieQuery::create();
        $criteria->add(SerieTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Serie (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setCvId($this->getCvId());
        $copyObj->setCvUrl($this->getCvUrl());
        $copyObj->setAddedOn($this->getAddedOn());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getIssues() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIssue($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserSeries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserSerie($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Serie Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Issue' == $relationName) {
            return $this->initIssues();
        }
        if ('UserSerie' == $relationName) {
            return $this->initUserSeries();
        }
    }

    /**
     * Clears out the collIssues collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addIssues()
     */
    public function clearIssues()
    {
        $this->collIssues = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collIssues collection loaded partially.
     */
    public function resetPartialIssues($v = true)
    {
        $this->collIssuesPartial = $v;
    }

    /**
     * Initializes the collIssues collection.
     *
     * By default this just sets the collIssues collection to an empty array (like clearcollIssues());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIssues($overrideExisting = true)
    {
        if (null !== $this->collIssues && !$overrideExisting) {
            return;
        }
        $this->collIssues = new ObjectCollection();
        $this->collIssues->setModel('\Issue');
    }

    /**
     * Gets an array of ChildIssue objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSerie is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildIssue[] List of ChildIssue objects
     * @throws PropelException
     */
    public function getIssues(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collIssuesPartial && !$this->isNew();
        if (null === $this->collIssues || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIssues) {
                // return empty collection
                $this->initIssues();
            } else {
                $collIssues = ChildIssueQuery::create(null, $criteria)
                    ->filterBySerie($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collIssuesPartial && count($collIssues)) {
                        $this->initIssues(false);

                        foreach ($collIssues as $obj) {
                            if (false == $this->collIssues->contains($obj)) {
                                $this->collIssues->append($obj);
                            }
                        }

                        $this->collIssuesPartial = true;
                    }

                    return $collIssues;
                }

                if ($partial && $this->collIssues) {
                    foreach ($this->collIssues as $obj) {
                        if ($obj->isNew()) {
                            $collIssues[] = $obj;
                        }
                    }
                }

                $this->collIssues = $collIssues;
                $this->collIssuesPartial = false;
            }
        }

        return $this->collIssues;
    }

    /**
     * Sets a collection of ChildIssue objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $issues A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSerie The current object (for fluent API support)
     */
    public function setIssues(Collection $issues, ConnectionInterface $con = null)
    {
        /** @var ChildIssue[] $issuesToDelete */
        $issuesToDelete = $this->getIssues(new Criteria(), $con)->diff($issues);


        $this->issuesScheduledForDeletion = $issuesToDelete;

        foreach ($issuesToDelete as $issueRemoved) {
            $issueRemoved->setSerie(null);
        }

        $this->collIssues = null;
        foreach ($issues as $issue) {
            $this->addIssue($issue);
        }

        $this->collIssues = $issues;
        $this->collIssuesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Issue objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Issue objects.
     * @throws PropelException
     */
    public function countIssues(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collIssuesPartial && !$this->isNew();
        if (null === $this->collIssues || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIssues) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIssues());
            }

            $query = ChildIssueQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySerie($this)
                ->count($con);
        }

        return count($this->collIssues);
    }

    /**
     * Method called to associate a ChildIssue object to this object
     * through the ChildIssue foreign key attribute.
     *
     * @param  ChildIssue $l ChildIssue
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function addIssue(ChildIssue $l)
    {
        if ($this->collIssues === null) {
            $this->initIssues();
            $this->collIssuesPartial = true;
        }

        if (!$this->collIssues->contains($l)) {
            $this->doAddIssue($l);
        }

        return $this;
    }

    /**
     * @param ChildIssue $issue The ChildIssue object to add.
     */
    protected function doAddIssue(ChildIssue $issue)
    {
        $this->collIssues[]= $issue;
        $issue->setSerie($this);
    }

    /**
     * @param  ChildIssue $issue The ChildIssue object to remove.
     * @return $this|ChildSerie The current object (for fluent API support)
     */
    public function removeIssue(ChildIssue $issue)
    {
        if ($this->getIssues()->contains($issue)) {
            $pos = $this->collIssues->search($issue);
            $this->collIssues->remove($pos);
            if (null === $this->issuesScheduledForDeletion) {
                $this->issuesScheduledForDeletion = clone $this->collIssues;
                $this->issuesScheduledForDeletion->clear();
            }
            $this->issuesScheduledForDeletion[]= clone $issue;
            $issue->setSerie(null);
        }

        return $this;
    }

    /**
     * Clears out the collUserSeries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserSeries()
     */
    public function clearUserSeries()
    {
        $this->collUserSeries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserSeries collection loaded partially.
     */
    public function resetPartialUserSeries($v = true)
    {
        $this->collUserSeriesPartial = $v;
    }

    /**
     * Initializes the collUserSeries collection.
     *
     * By default this just sets the collUserSeries collection to an empty array (like clearcollUserSeries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserSeries($overrideExisting = true)
    {
        if (null !== $this->collUserSeries && !$overrideExisting) {
            return;
        }
        $this->collUserSeries = new ObjectCollection();
        $this->collUserSeries->setModel('\UserSerie');
    }

    /**
     * Gets an array of ChildUserSerie objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSerie is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserSerie[] List of ChildUserSerie objects
     * @throws PropelException
     */
    public function getUserSeries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSeriesPartial && !$this->isNew();
        if (null === $this->collUserSeries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserSeries) {
                // return empty collection
                $this->initUserSeries();
            } else {
                $collUserSeries = ChildUserSerieQuery::create(null, $criteria)
                    ->filterBySerie($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserSeriesPartial && count($collUserSeries)) {
                        $this->initUserSeries(false);

                        foreach ($collUserSeries as $obj) {
                            if (false == $this->collUserSeries->contains($obj)) {
                                $this->collUserSeries->append($obj);
                            }
                        }

                        $this->collUserSeriesPartial = true;
                    }

                    return $collUserSeries;
                }

                if ($partial && $this->collUserSeries) {
                    foreach ($this->collUserSeries as $obj) {
                        if ($obj->isNew()) {
                            $collUserSeries[] = $obj;
                        }
                    }
                }

                $this->collUserSeries = $collUserSeries;
                $this->collUserSeriesPartial = false;
            }
        }

        return $this->collUserSeries;
    }

    /**
     * Sets a collection of ChildUserSerie objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userSeries A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildSerie The current object (for fluent API support)
     */
    public function setUserSeries(Collection $userSeries, ConnectionInterface $con = null)
    {
        /** @var ChildUserSerie[] $userSeriesToDelete */
        $userSeriesToDelete = $this->getUserSeries(new Criteria(), $con)->diff($userSeries);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userSeriesScheduledForDeletion = clone $userSeriesToDelete;

        foreach ($userSeriesToDelete as $userSerieRemoved) {
            $userSerieRemoved->setSerie(null);
        }

        $this->collUserSeries = null;
        foreach ($userSeries as $userSerie) {
            $this->addUserSerie($userSerie);
        }

        $this->collUserSeries = $userSeries;
        $this->collUserSeriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserSerie objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserSerie objects.
     * @throws PropelException
     */
    public function countUserSeries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserSeriesPartial && !$this->isNew();
        if (null === $this->collUserSeries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserSeries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserSeries());
            }

            $query = ChildUserSerieQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySerie($this)
                ->count($con);
        }

        return count($this->collUserSeries);
    }

    /**
     * Method called to associate a ChildUserSerie object to this object
     * through the ChildUserSerie foreign key attribute.
     *
     * @param  ChildUserSerie $l ChildUserSerie
     * @return $this|\Serie The current object (for fluent API support)
     */
    public function addUserSerie(ChildUserSerie $l)
    {
        if ($this->collUserSeries === null) {
            $this->initUserSeries();
            $this->collUserSeriesPartial = true;
        }

        if (!$this->collUserSeries->contains($l)) {
            $this->doAddUserSerie($l);
        }

        return $this;
    }

    /**
     * @param ChildUserSerie $userSerie The ChildUserSerie object to add.
     */
    protected function doAddUserSerie(ChildUserSerie $userSerie)
    {
        $this->collUserSeries[]= $userSerie;
        $userSerie->setSerie($this);
    }

    /**
     * @param  ChildUserSerie $userSerie The ChildUserSerie object to remove.
     * @return $this|ChildSerie The current object (for fluent API support)
     */
    public function removeUserSerie(ChildUserSerie $userSerie)
    {
        if ($this->getUserSeries()->contains($userSerie)) {
            $pos = $this->collUserSeries->search($userSerie);
            $this->collUserSeries->remove($pos);
            if (null === $this->userSeriesScheduledForDeletion) {
                $this->userSeriesScheduledForDeletion = clone $this->collUserSeries;
                $this->userSeriesScheduledForDeletion->clear();
            }
            $this->userSeriesScheduledForDeletion[]= clone $userSerie;
            $userSerie->setSerie(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Serie is new, it will return
     * an empty collection; or if this Serie has previously
     * been saved, it will retrieve related UserSeries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Serie.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserSerie[] List of ChildUserSerie objects
     */
    public function getUserSeriesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserSerieQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserSeries($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUsers crossRef collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $this->collUsers = new ObjectCollection();
        $this->collUsersPartial = true;

        $this->collUsers->setModel('\User');
    }

    /**
     * Checks if the collUsers collection is loaded.
     *
     * @return bool
     */
    public function isUsersLoaded()
    {
        return null !== $this->collUsers;
    }

    /**
     * Gets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSerie is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                }
            } else {

                $query = ChildUserQuery::create(null, $criteria)
                    ->filterBySerie($this);
                $collUsers = $query->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUsers as $obj) {
                        if (!$collUsers->contains($obj)) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $users A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildSerie The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($usersScheduledForDeletion as $toDelete) {
            $this->removeUser($toDelete);
        }

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsersPartial = false;
        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUsers());
                }

                $query = ChildUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterBySerie($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the comics_user_serie cross reference table.
     *
     * @param ChildUser $user
     * @return ChildSerie The current object (for fluent API support)
     */
    public function addUser(ChildUser $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->getUsers()->contains($user)) {
            // only add it if the **same** object is not already associated
            $this->collUsers->push($user);
            $this->doAddUser($user);
        }

        return $this;
    }

    /**
     *
     * @param ChildUser $user
     */
    protected function doAddUser(ChildUser $user)
    {
        $userSerie = new ChildUserSerie();

        $userSerie->setUser($user);

        $userSerie->setSerie($this);

        $this->addUserSerie($userSerie);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isSeriesLoaded()) {
            $user->initSeries();
            $user->getSeries()->push($this);
        } elseif (!$user->getSeries()->contains($this)) {
            $user->getSeries()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the comics_user_serie cross reference table.
     *
     * @param ChildUser $user
     * @return ChildSerie The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) { $userSerie = new ChildUserSerie();

            $userSerie->setUser($user);
            if ($user->isSeriesLoaded()) {
                //remove the back reference if available
                $user->getSeries()->removeObject($this);
            }

            $userSerie->setSerie($this);
            $this->removeUserSerie(clone $userSerie);
            $userSerie->clear();

            $this->collUsers->remove($this->collUsers->search($user));

            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }

            $this->usersScheduledForDeletion->push($user);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->cv_id = null;
        $this->cv_url = null;
        $this->added_on = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collIssues) {
                foreach ($this->collIssues as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserSeries) {
                foreach ($this->collUserSeries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collIssues = null;
        $this->collUserSeries = null;
        $this->collUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SerieTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
