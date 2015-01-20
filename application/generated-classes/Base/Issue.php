<?php

namespace Base;

use \Issue as ChildIssue;
use \IssueQuery as ChildIssueQuery;
use \Serie as ChildSerie;
use \SerieQuery as ChildSerieQuery;
use \User as ChildUser;
use \UserIssue as ChildUserIssue;
use \UserIssueQuery as ChildUserIssueQuery;
use \UserQuery as ChildUserQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\IssueTableMap;
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
 * Base class that represents a row from the 'comics_issue' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Issue implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\IssueTableMap';


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
     * The value for the issue_number field.
     * @var        string
     */
    protected $issue_number;

    /**
     * The value for the serie_id field.
     * @var        int
     */
    protected $serie_id;

    /**
     * The value for the pub_date field.
     * @var        \DateTime
     */
    protected $pub_date;

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
     * @var        ChildSerie
     */
    protected $aSerie;

    /**
     * @var        ObjectCollection|ChildUserIssue[] Collection to store aggregation of ChildUserIssue objects.
     */
    protected $collUserIssues;
    protected $collUserIssuesPartial;

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
     * @var ObjectCollection|ChildUserIssue[]
     */
    protected $userIssuesScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Issue object.
     */
    public function __construct()
    {
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
     * Compares this with another <code>Issue</code> instance.  If
     * <code>obj</code> is an instance of <code>Issue</code>, delegates to
     * <code>equals(Issue)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Issue The current object, for fluid interface
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
     * Get the [issue_number] column value.
     *
     * @return string
     */
    public function getIssueNumber()
    {
        return $this->issue_number;
    }

    /**
     * Get the [serie_id] column value.
     *
     * @return int
     */
    public function getSerieId()
    {
        return $this->serie_id;
    }

    /**
     * Get the [optionally formatted] temporal [pub_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPubDate($format = NULL)
    {
        if ($format === null) {
            return $this->pub_date;
        } else {
            return $this->pub_date instanceof \DateTime ? $this->pub_date->format($format) : null;
        }
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
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[IssueTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[IssueTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [issue_number] column.
     *
     * @param  string $v new value
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setIssueNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->issue_number !== $v) {
            $this->issue_number = $v;
            $this->modifiedColumns[IssueTableMap::COL_ISSUE_NUMBER] = true;
        }

        return $this;
    } // setIssueNumber()

    /**
     * Set the value of [serie_id] column.
     *
     * @param  int $v new value
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setSerieId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->serie_id !== $v) {
            $this->serie_id = $v;
            $this->modifiedColumns[IssueTableMap::COL_SERIE_ID] = true;
        }

        if ($this->aSerie !== null && $this->aSerie->getId() !== $v) {
            $this->aSerie = null;
        }

        return $this;
    } // setSerieId()

    /**
     * Sets the value of [pub_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setPubDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->pub_date !== null || $dt !== null) {
            if ($dt !== $this->pub_date) {
                $this->pub_date = $dt;
                $this->modifiedColumns[IssueTableMap::COL_PUB_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setPubDate()

    /**
     * Set the value of [cv_id] column.
     *
     * @param  string $v new value
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setCvId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cv_id !== $v) {
            $this->cv_id = $v;
            $this->modifiedColumns[IssueTableMap::COL_CV_ID] = true;
        }

        return $this;
    } // setCvId()

    /**
     * Set the value of [cv_url] column.
     *
     * @param  string $v new value
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function setCvUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cv_url !== $v) {
            $this->cv_url = $v;
            $this->modifiedColumns[IssueTableMap::COL_CV_URL] = true;
        }

        return $this;
    } // setCvUrl()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : IssueTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : IssueTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : IssueTableMap::translateFieldName('IssueNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->issue_number = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : IssueTableMap::translateFieldName('SerieId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->serie_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : IssueTableMap::translateFieldName('PubDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->pub_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : IssueTableMap::translateFieldName('CvId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cv_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : IssueTableMap::translateFieldName('CvUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cv_url = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = IssueTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Issue'), 0, $e);
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
        if ($this->aSerie !== null && $this->serie_id !== $this->aSerie->getId()) {
            $this->aSerie = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(IssueTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildIssueQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSerie = null;
            $this->collUserIssues = null;

            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Issue::setDeleted()
     * @see Issue::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(IssueTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildIssueQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(IssueTableMap::DATABASE_NAME);
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
                IssueTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aSerie !== null) {
                if ($this->aSerie->isModified() || $this->aSerie->isNew()) {
                    $affectedRows += $this->aSerie->save($con);
                }
                $this->setSerie($this->aSerie);
            }

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

                    \UserIssueQuery::create()
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


            if ($this->userIssuesScheduledForDeletion !== null) {
                if (!$this->userIssuesScheduledForDeletion->isEmpty()) {
                    \UserIssueQuery::create()
                        ->filterByPrimaryKeys($this->userIssuesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userIssuesScheduledForDeletion = null;
                }
            }

            if ($this->collUserIssues !== null) {
                foreach ($this->collUserIssues as $referrerFK) {
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

        $this->modifiedColumns[IssueTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . IssueTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(IssueTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(IssueTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(IssueTableMap::COL_ISSUE_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'issue_number';
        }
        if ($this->isColumnModified(IssueTableMap::COL_SERIE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'serie_id';
        }
        if ($this->isColumnModified(IssueTableMap::COL_PUB_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'pub_date';
        }
        if ($this->isColumnModified(IssueTableMap::COL_CV_ID)) {
            $modifiedColumns[':p' . $index++]  = 'cv_id';
        }
        if ($this->isColumnModified(IssueTableMap::COL_CV_URL)) {
            $modifiedColumns[':p' . $index++]  = 'cv_url';
        }

        $sql = sprintf(
            'INSERT INTO comics_issue (%s) VALUES (%s)',
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
                    case 'issue_number':
                        $stmt->bindValue($identifier, $this->issue_number, PDO::PARAM_STR);
                        break;
                    case 'serie_id':
                        $stmt->bindValue($identifier, $this->serie_id, PDO::PARAM_INT);
                        break;
                    case 'pub_date':
                        $stmt->bindValue($identifier, $this->pub_date ? $this->pub_date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'cv_id':
                        $stmt->bindValue($identifier, $this->cv_id, PDO::PARAM_STR);
                        break;
                    case 'cv_url':
                        $stmt->bindValue($identifier, $this->cv_url, PDO::PARAM_STR);
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
        $pos = IssueTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getIssueNumber();
                break;
            case 3:
                return $this->getSerieId();
                break;
            case 4:
                return $this->getPubDate();
                break;
            case 5:
                return $this->getCvId();
                break;
            case 6:
                return $this->getCvUrl();
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

        if (isset($alreadyDumpedObjects['Issue'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Issue'][$this->hashCode()] = true;
        $keys = IssueTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getIssueNumber(),
            $keys[3] => $this->getSerieId(),
            $keys[4] => $this->getPubDate(),
            $keys[5] => $this->getCvId(),
            $keys[6] => $this->getCvUrl(),
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
            if (null !== $this->aSerie) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'serie';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comics_serie';
                        break;
                    default:
                        $key = 'Serie';
                }

                $result[$key] = $this->aSerie->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserIssues) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'userIssues';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'comics_user_issues';
                        break;
                    default:
                        $key = 'UserIssues';
                }

                $result[$key] = $this->collUserIssues->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Issue
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = IssueTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Issue
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
                $this->setIssueNumber($value);
                break;
            case 3:
                $this->setSerieId($value);
                break;
            case 4:
                $this->setPubDate($value);
                break;
            case 5:
                $this->setCvId($value);
                break;
            case 6:
                $this->setCvUrl($value);
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
        $keys = IssueTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setIssueNumber($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSerieId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPubDate($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCvId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCvUrl($arr[$keys[6]]);
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
     * @return $this|\Issue The current object, for fluid interface
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
        $criteria = new Criteria(IssueTableMap::DATABASE_NAME);

        if ($this->isColumnModified(IssueTableMap::COL_ID)) {
            $criteria->add(IssueTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(IssueTableMap::COL_TITLE)) {
            $criteria->add(IssueTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(IssueTableMap::COL_ISSUE_NUMBER)) {
            $criteria->add(IssueTableMap::COL_ISSUE_NUMBER, $this->issue_number);
        }
        if ($this->isColumnModified(IssueTableMap::COL_SERIE_ID)) {
            $criteria->add(IssueTableMap::COL_SERIE_ID, $this->serie_id);
        }
        if ($this->isColumnModified(IssueTableMap::COL_PUB_DATE)) {
            $criteria->add(IssueTableMap::COL_PUB_DATE, $this->pub_date);
        }
        if ($this->isColumnModified(IssueTableMap::COL_CV_ID)) {
            $criteria->add(IssueTableMap::COL_CV_ID, $this->cv_id);
        }
        if ($this->isColumnModified(IssueTableMap::COL_CV_URL)) {
            $criteria->add(IssueTableMap::COL_CV_URL, $this->cv_url);
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
        $criteria = ChildIssueQuery::create();
        $criteria->add(IssueTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Issue (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setIssueNumber($this->getIssueNumber());
        $copyObj->setSerieId($this->getSerieId());
        $copyObj->setPubDate($this->getPubDate());
        $copyObj->setCvId($this->getCvId());
        $copyObj->setCvUrl($this->getCvUrl());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserIssues() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserIssue($relObj->copy($deepCopy));
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
     * @return \Issue Clone of current object.
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
     * Declares an association between this object and a ChildSerie object.
     *
     * @param  ChildSerie $v
     * @return $this|\Issue The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSerie(ChildSerie $v = null)
    {
        if ($v === null) {
            $this->setSerieId(NULL);
        } else {
            $this->setSerieId($v->getId());
        }

        $this->aSerie = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSerie object, it will not be re-added.
        if ($v !== null) {
            $v->addIssue($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSerie object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildSerie The associated ChildSerie object.
     * @throws PropelException
     */
    public function getSerie(ConnectionInterface $con = null)
    {
        if ($this->aSerie === null && ($this->serie_id !== null)) {
            $this->aSerie = ChildSerieQuery::create()->findPk($this->serie_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSerie->addIssues($this);
             */
        }

        return $this->aSerie;
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
        if ('UserIssue' == $relationName) {
            return $this->initUserIssues();
        }
    }

    /**
     * Clears out the collUserIssues collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserIssues()
     */
    public function clearUserIssues()
    {
        $this->collUserIssues = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUserIssues collection loaded partially.
     */
    public function resetPartialUserIssues($v = true)
    {
        $this->collUserIssuesPartial = $v;
    }

    /**
     * Initializes the collUserIssues collection.
     *
     * By default this just sets the collUserIssues collection to an empty array (like clearcollUserIssues());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserIssues($overrideExisting = true)
    {
        if (null !== $this->collUserIssues && !$overrideExisting) {
            return;
        }
        $this->collUserIssues = new ObjectCollection();
        $this->collUserIssues->setModel('\UserIssue');
    }

    /**
     * Gets an array of ChildUserIssue objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildIssue is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUserIssue[] List of ChildUserIssue objects
     * @throws PropelException
     */
    public function getUserIssues(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUserIssuesPartial && !$this->isNew();
        if (null === $this->collUserIssues || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserIssues) {
                // return empty collection
                $this->initUserIssues();
            } else {
                $collUserIssues = ChildUserIssueQuery::create(null, $criteria)
                    ->filterByIssue($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUserIssuesPartial && count($collUserIssues)) {
                        $this->initUserIssues(false);

                        foreach ($collUserIssues as $obj) {
                            if (false == $this->collUserIssues->contains($obj)) {
                                $this->collUserIssues->append($obj);
                            }
                        }

                        $this->collUserIssuesPartial = true;
                    }

                    return $collUserIssues;
                }

                if ($partial && $this->collUserIssues) {
                    foreach ($this->collUserIssues as $obj) {
                        if ($obj->isNew()) {
                            $collUserIssues[] = $obj;
                        }
                    }
                }

                $this->collUserIssues = $collUserIssues;
                $this->collUserIssuesPartial = false;
            }
        }

        return $this->collUserIssues;
    }

    /**
     * Sets a collection of ChildUserIssue objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $userIssues A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildIssue The current object (for fluent API support)
     */
    public function setUserIssues(Collection $userIssues, ConnectionInterface $con = null)
    {
        /** @var ChildUserIssue[] $userIssuesToDelete */
        $userIssuesToDelete = $this->getUserIssues(new Criteria(), $con)->diff($userIssues);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->userIssuesScheduledForDeletion = clone $userIssuesToDelete;

        foreach ($userIssuesToDelete as $userIssueRemoved) {
            $userIssueRemoved->setIssue(null);
        }

        $this->collUserIssues = null;
        foreach ($userIssues as $userIssue) {
            $this->addUserIssue($userIssue);
        }

        $this->collUserIssues = $userIssues;
        $this->collUserIssuesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UserIssue objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UserIssue objects.
     * @throws PropelException
     */
    public function countUserIssues(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUserIssuesPartial && !$this->isNew();
        if (null === $this->collUserIssues || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserIssues) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserIssues());
            }

            $query = ChildUserIssueQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIssue($this)
                ->count($con);
        }

        return count($this->collUserIssues);
    }

    /**
     * Method called to associate a ChildUserIssue object to this object
     * through the ChildUserIssue foreign key attribute.
     *
     * @param  ChildUserIssue $l ChildUserIssue
     * @return $this|\Issue The current object (for fluent API support)
     */
    public function addUserIssue(ChildUserIssue $l)
    {
        if ($this->collUserIssues === null) {
            $this->initUserIssues();
            $this->collUserIssuesPartial = true;
        }

        if (!$this->collUserIssues->contains($l)) {
            $this->doAddUserIssue($l);
        }

        return $this;
    }

    /**
     * @param ChildUserIssue $userIssue The ChildUserIssue object to add.
     */
    protected function doAddUserIssue(ChildUserIssue $userIssue)
    {
        $this->collUserIssues[]= $userIssue;
        $userIssue->setIssue($this);
    }

    /**
     * @param  ChildUserIssue $userIssue The ChildUserIssue object to remove.
     * @return $this|ChildIssue The current object (for fluent API support)
     */
    public function removeUserIssue(ChildUserIssue $userIssue)
    {
        if ($this->getUserIssues()->contains($userIssue)) {
            $pos = $this->collUserIssues->search($userIssue);
            $this->collUserIssues->remove($pos);
            if (null === $this->userIssuesScheduledForDeletion) {
                $this->userIssuesScheduledForDeletion = clone $this->collUserIssues;
                $this->userIssuesScheduledForDeletion->clear();
            }
            $this->userIssuesScheduledForDeletion[]= clone $userIssue;
            $userIssue->setIssue(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Issue is new, it will return
     * an empty collection; or if this Issue has previously
     * been saved, it will retrieve related UserIssues from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Issue.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserIssue[] List of ChildUserIssue objects
     */
    public function getUserIssuesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserIssueQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUserIssues($query, $con);
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
     * to the current object by way of the comics_user_issue cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildIssue is new, it will return
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
                    ->filterByIssue($this);
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
     * to the current object by way of the comics_user_issue cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $users A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildIssue The current object (for fluent API support)
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
     * to the current object by way of the comics_user_issue cross-reference table.
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
                    ->filterByIssue($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the comics_user_issue cross reference table.
     *
     * @param ChildUser $user
     * @return ChildIssue The current object (for fluent API support)
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
        $userIssue = new ChildUserIssue();

        $userIssue->setUser($user);

        $userIssue->setIssue($this);

        $this->addUserIssue($userIssue);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isIssuesLoaded()) {
            $user->initIssues();
            $user->getIssues()->push($this);
        } elseif (!$user->getIssues()->contains($this)) {
            $user->getIssues()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the comics_user_issue cross reference table.
     *
     * @param ChildUser $user
     * @return ChildIssue The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) { $userIssue = new ChildUserIssue();

            $userIssue->setUser($user);
            if ($user->isIssuesLoaded()) {
                //remove the back reference if available
                $user->getIssues()->removeObject($this);
            }

            $userIssue->setIssue($this);
            $this->removeUserIssue(clone $userIssue);
            $userIssue->clear();

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
        if (null !== $this->aSerie) {
            $this->aSerie->removeIssue($this);
        }
        $this->id = null;
        $this->title = null;
        $this->issue_number = null;
        $this->serie_id = null;
        $this->pub_date = null;
        $this->cv_id = null;
        $this->cv_url = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
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
            if ($this->collUserIssues) {
                foreach ($this->collUserIssues as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserIssues = null;
        $this->collUsers = null;
        $this->aSerie = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(IssueTableMap::DEFAULT_STRING_FORMAT);
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
