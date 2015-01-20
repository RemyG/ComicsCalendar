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
use \UserSerie as ChildUserSerie;
use \UserSerieQuery as ChildUserSerieQuery;
use \DateTime;
use \Exception;
use \PDO;
use Map\UserTableMap;
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
 * Base class that represents a row from the 'comics_user' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\UserTableMap';


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
     * The value for the login field.
     * @var        string
     */
    protected $login;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the auth_key field.
     * @var        string
     */
    protected $auth_key;

    /**
     * The value for the last_seen_on field.
     * Note: this column has a database default value of: NULL
     * @var        \DateTime
     */
    protected $last_seen_on;

    /**
     * @var        ObjectCollection|ChildUserSerie[] Collection to store aggregation of ChildUserSerie objects.
     */
    protected $collUserSeries;
    protected $collUserSeriesPartial;

    /**
     * @var        ObjectCollection|ChildUserIssue[] Collection to store aggregation of ChildUserIssue objects.
     */
    protected $collUserIssues;
    protected $collUserIssuesPartial;

    /**
     * @var        ObjectCollection|ChildSerie[] Cross Collection to store aggregation of ChildSerie objects.
     */
    protected $collSeries;

    /**
     * @var bool
     */
    protected $collSeriesPartial;

    /**
     * @var        ObjectCollection|ChildIssue[] Cross Collection to store aggregation of ChildIssue objects.
     */
    protected $collIssues;

    /**
     * @var bool
     */
    protected $collIssuesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSerie[]
     */
    protected $seriesScheduledForDeletion = null;

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
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUserIssue[]
     */
    protected $userIssuesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->last_seen_on = PropelDateTime::newInstance(NULL, null, 'DateTime');
    }

    /**
     * Initializes internal state of Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [login] column value.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [auth_key] column value.
     *
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Get the [optionally formatted] temporal [last_seen_on] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastSeenOn($format = NULL)
    {
        if ($format === null) {
            return $this->last_seen_on;
        } else {
            return $this->last_seen_on instanceof \DateTime ? $this->last_seen_on->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [login] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setLogin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->login !== $v) {
            $this->login = $v;
            $this->modifiedColumns[UserTableMap::COL_LOGIN] = true;
        }

        return $this;
    } // setLogin()

    /**
     * Set the value of [password] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [auth_key] column.
     *
     * @param  string $v new value
     * @return $this|\User The current object (for fluent API support)
     */
    public function setAuthKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->auth_key !== $v) {
            $this->auth_key = $v;
            $this->modifiedColumns[UserTableMap::COL_AUTH_KEY] = true;
        }

        return $this;
    } // setAuthKey()

    /**
     * Sets the value of [last_seen_on] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\User The current object (for fluent API support)
     */
    public function setLastSeenOn($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_seen_on !== null || $dt !== null) {
            if ( ($dt != $this->last_seen_on) // normalized values don't match
                || ($dt->format('Y-m-d H:i:s') === NULL) // or the entered value matches the default
                 ) {
                $this->last_seen_on = $dt;
                $this->modifiedColumns[UserTableMap::COL_LAST_SEEN_ON] = true;
            }
        } // if either are not null

        return $this;
    } // setLastSeenOn()

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
            if ($this->last_seen_on && $this->last_seen_on->format('Y-m-d H:i:s') !== NULL) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Login', TableMap::TYPE_PHPNAME, $indexType)];
            $this->login = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('AuthKey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->auth_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('LastSeenOn', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->last_seen_on = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUserSeries = null;

            $this->collUserIssues = null;

            $this->collSeries = null;
            $this->collIssues = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->seriesScheduledForDeletion !== null) {
                if (!$this->seriesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->seriesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \UserSerieQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->seriesScheduledForDeletion = null;
                }

            }

            if ($this->collSeries) {
                foreach ($this->collSeries as $serie) {
                    if (!$serie->isDeleted() && ($serie->isNew() || $serie->isModified())) {
                        $serie->save($con);
                    }
                }
            }


            if ($this->issuesScheduledForDeletion !== null) {
                if (!$this->issuesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->issuesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \UserIssueQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->issuesScheduledForDeletion = null;
                }

            }

            if ($this->collIssues) {
                foreach ($this->collIssues as $issue) {
                    if (!$issue->isDeleted() && ($issue->isNew() || $issue->isModified())) {
                        $issue->save($con);
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN)) {
            $modifiedColumns[':p' . $index++]  = 'login';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_AUTH_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'auth_key';
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_SEEN_ON)) {
            $modifiedColumns[':p' . $index++]  = 'last_seen_on';
        }

        $sql = sprintf(
            'INSERT INTO comics_user (%s) VALUES (%s)',
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
                    case 'login':
                        $stmt->bindValue($identifier, $this->login, PDO::PARAM_STR);
                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'auth_key':
                        $stmt->bindValue($identifier, $this->auth_key, PDO::PARAM_STR);
                        break;
                    case 'last_seen_on':
                        $stmt->bindValue($identifier, $this->last_seen_on ? $this->last_seen_on->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getLogin();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getEmail();
                break;
            case 4:
                return $this->getAuthKey();
                break;
            case 5:
                return $this->getLastSeenOn();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLogin(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getEmail(),
            $keys[4] => $this->getAuthKey(),
            $keys[5] => $this->getLastSeenOn(),
        );

        $utc = new \DateTimeZone('utc');
        if ($result[$keys[5]] instanceof \DateTime) {
            // When changing timezone we don't want to change existing instances
            $dateTime = clone $result[$keys[5]];
            $result[$keys[5]] = $dateTime->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
     * @return $this|\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setLogin($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setEmail($value);
                break;
            case 4:
                $this->setAuthKey($value);
                break;
            case 5:
                $this->setLastSeenOn($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setLogin($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmail($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAuthKey($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLastSeenOn($arr[$keys[5]]);
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
     * @return $this|\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_LOGIN)) {
            $criteria->add(UserTableMap::COL_LOGIN, $this->login);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_AUTH_KEY)) {
            $criteria->add(UserTableMap::COL_AUTH_KEY, $this->auth_key);
        }
        if ($this->isColumnModified(UserTableMap::COL_LAST_SEEN_ON)) {
            $criteria->add(UserTableMap::COL_LAST_SEEN_ON, $this->last_seen_on);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLogin($this->getLogin());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setAuthKey($this->getAuthKey());
        $copyObj->setLastSeenOn($this->getLastSeenOn());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUserSeries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserSerie($relObj->copy($deepCopy));
                }
            }

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
     * @return \User Clone of current object.
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
        if ('UserSerie' == $relationName) {
            return $this->initUserSeries();
        }
        if ('UserIssue' == $relationName) {
            return $this->initUserIssues();
        }
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $userSerieRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserSeries);
    }

    /**
     * Method called to associate a ChildUserSerie object to this object
     * through the ChildUserSerie foreign key attribute.
     *
     * @param  ChildUserSerie $l ChildUserSerie
     * @return $this|\User The current object (for fluent API support)
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
        $userSerie->setUser($this);
    }

    /**
     * @param  ChildUserSerie $userSerie The ChildUserSerie object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $userSerie->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserSeries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserSerie[] List of ChildUserSerie objects
     */
    public function getUserSeriesJoinSerie(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserSerieQuery::create(null, $criteria);
        $query->joinWith('Serie', $joinBehavior);

        return $this->getUserSeries($query, $con);
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
     * If this ChildUser is new, it will return
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
                    ->filterByUser($this)
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
     * @return $this|ChildUser The current object (for fluent API support)
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
            $userIssueRemoved->setUser(null);
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
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collUserIssues);
    }

    /**
     * Method called to associate a ChildUserIssue object to this object
     * through the ChildUserIssue foreign key attribute.
     *
     * @param  ChildUserIssue $l ChildUserIssue
     * @return $this|\User The current object (for fluent API support)
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
        $userIssue->setUser($this);
    }

    /**
     * @param  ChildUserIssue $userIssue The ChildUserIssue object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
            $userIssue->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related UserIssues from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUserIssue[] List of ChildUserIssue objects
     */
    public function getUserIssuesJoinIssue(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildUserIssueQuery::create(null, $criteria);
        $query->joinWith('Issue', $joinBehavior);

        return $this->getUserIssues($query, $con);
    }

    /**
     * Clears out the collSeries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSeries()
     */
    public function clearSeries()
    {
        $this->collSeries = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collSeries crossRef collection.
     *
     * By default this just sets the collSeries collection to an empty collection (like clearSeries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initSeries()
    {
        $this->collSeries = new ObjectCollection();
        $this->collSeriesPartial = true;

        $this->collSeries->setModel('\Serie');
    }

    /**
     * Checks if the collSeries collection is loaded.
     *
     * @return bool
     */
    public function isSeriesLoaded()
    {
        return null !== $this->collSeries;
    }

    /**
     * Gets a collection of ChildSerie objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildSerie[] List of ChildSerie objects
     */
    public function getSeries(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSeriesPartial && !$this->isNew();
        if (null === $this->collSeries || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSeries) {
                    $this->initSeries();
                }
            } else {

                $query = ChildSerieQuery::create(null, $criteria)
                    ->filterByUser($this);
                $collSeries = $query->find($con);
                if (null !== $criteria) {
                    return $collSeries;
                }

                if ($partial && $this->collSeries) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collSeries as $obj) {
                        if (!$collSeries->contains($obj)) {
                            $collSeries[] = $obj;
                        }
                    }
                }

                $this->collSeries = $collSeries;
                $this->collSeriesPartial = false;
            }
        }

        return $this->collSeries;
    }

    /**
     * Sets a collection of Serie objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $series A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setSeries(Collection $series, ConnectionInterface $con = null)
    {
        $this->clearSeries();
        $currentSeries = $this->getSeries();

        $seriesScheduledForDeletion = $currentSeries->diff($series);

        foreach ($seriesScheduledForDeletion as $toDelete) {
            $this->removeSerie($toDelete);
        }

        foreach ($series as $serie) {
            if (!$currentSeries->contains($serie)) {
                $this->doAddSerie($serie);
            }
        }

        $this->collSeriesPartial = false;
        $this->collSeries = $series;

        return $this;
    }

    /**
     * Gets the number of Serie objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Serie objects
     */
    public function countSeries(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSeriesPartial && !$this->isNew();
        if (null === $this->collSeries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSeries) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getSeries());
                }

                $query = ChildSerieQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collSeries);
        }
    }

    /**
     * Associate a ChildSerie to this object
     * through the comics_user_serie cross reference table.
     *
     * @param ChildSerie $serie
     * @return ChildUser The current object (for fluent API support)
     */
    public function addSerie(ChildSerie $serie)
    {
        if ($this->collSeries === null) {
            $this->initSeries();
        }

        if (!$this->getSeries()->contains($serie)) {
            // only add it if the **same** object is not already associated
            $this->collSeries->push($serie);
            $this->doAddSerie($serie);
        }

        return $this;
    }

    /**
     *
     * @param ChildSerie $serie
     */
    protected function doAddSerie(ChildSerie $serie)
    {
        $userSerie = new ChildUserSerie();

        $userSerie->setSerie($serie);

        $userSerie->setUser($this);

        $this->addUserSerie($userSerie);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$serie->isUsersLoaded()) {
            $serie->initUsers();
            $serie->getUsers()->push($this);
        } elseif (!$serie->getUsers()->contains($this)) {
            $serie->getUsers()->push($this);
        }

    }

    /**
     * Remove serie of this object
     * through the comics_user_serie cross reference table.
     *
     * @param ChildSerie $serie
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeSerie(ChildSerie $serie)
    {
        if ($this->getSeries()->contains($serie)) { $userSerie = new ChildUserSerie();

            $userSerie->setSerie($serie);
            if ($serie->isUsersLoaded()) {
                //remove the back reference if available
                $serie->getUsers()->removeObject($this);
            }

            $userSerie->setUser($this);
            $this->removeUserSerie(clone $userSerie);
            $userSerie->clear();

            $this->collSeries->remove($this->collSeries->search($serie));

            if (null === $this->seriesScheduledForDeletion) {
                $this->seriesScheduledForDeletion = clone $this->collSeries;
                $this->seriesScheduledForDeletion->clear();
            }

            $this->seriesScheduledForDeletion->push($serie);
        }


        return $this;
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
     * Initializes the collIssues crossRef collection.
     *
     * By default this just sets the collIssues collection to an empty collection (like clearIssues());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initIssues()
    {
        $this->collIssues = new ObjectCollection();
        $this->collIssuesPartial = true;

        $this->collIssues->setModel('\Issue');
    }

    /**
     * Checks if the collIssues collection is loaded.
     *
     * @return bool
     */
    public function isIssuesLoaded()
    {
        return null !== $this->collIssues;
    }

    /**
     * Gets a collection of ChildIssue objects related by a many-to-many relationship
     * to the current object by way of the comics_user_issue cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildIssue[] List of ChildIssue objects
     */
    public function getIssues(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collIssuesPartial && !$this->isNew();
        if (null === $this->collIssues || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collIssues) {
                    $this->initIssues();
                }
            } else {

                $query = ChildIssueQuery::create(null, $criteria)
                    ->filterByUser($this);
                $collIssues = $query->find($con);
                if (null !== $criteria) {
                    return $collIssues;
                }

                if ($partial && $this->collIssues) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collIssues as $obj) {
                        if (!$collIssues->contains($obj)) {
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
     * Sets a collection of Issue objects related by a many-to-many relationship
     * to the current object by way of the comics_user_issue cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $issues A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setIssues(Collection $issues, ConnectionInterface $con = null)
    {
        $this->clearIssues();
        $currentIssues = $this->getIssues();

        $issuesScheduledForDeletion = $currentIssues->diff($issues);

        foreach ($issuesScheduledForDeletion as $toDelete) {
            $this->removeIssue($toDelete);
        }

        foreach ($issues as $issue) {
            if (!$currentIssues->contains($issue)) {
                $this->doAddIssue($issue);
            }
        }

        $this->collIssuesPartial = false;
        $this->collIssues = $issues;

        return $this;
    }

    /**
     * Gets the number of Issue objects related by a many-to-many relationship
     * to the current object by way of the comics_user_issue cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Issue objects
     */
    public function countIssues(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collIssuesPartial && !$this->isNew();
        if (null === $this->collIssues || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIssues) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getIssues());
                }

                $query = ChildIssueQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collIssues);
        }
    }

    /**
     * Associate a ChildIssue to this object
     * through the comics_user_issue cross reference table.
     *
     * @param ChildIssue $issue
     * @return ChildUser The current object (for fluent API support)
     */
    public function addIssue(ChildIssue $issue)
    {
        if ($this->collIssues === null) {
            $this->initIssues();
        }

        if (!$this->getIssues()->contains($issue)) {
            // only add it if the **same** object is not already associated
            $this->collIssues->push($issue);
            $this->doAddIssue($issue);
        }

        return $this;
    }

    /**
     *
     * @param ChildIssue $issue
     */
    protected function doAddIssue(ChildIssue $issue)
    {
        $userIssue = new ChildUserIssue();

        $userIssue->setIssue($issue);

        $userIssue->setUser($this);

        $this->addUserIssue($userIssue);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$issue->isUsersLoaded()) {
            $issue->initUsers();
            $issue->getUsers()->push($this);
        } elseif (!$issue->getUsers()->contains($this)) {
            $issue->getUsers()->push($this);
        }

    }

    /**
     * Remove issue of this object
     * through the comics_user_issue cross reference table.
     *
     * @param ChildIssue $issue
     * @return ChildUser The current object (for fluent API support)
     */
    public function removeIssue(ChildIssue $issue)
    {
        if ($this->getIssues()->contains($issue)) { $userIssue = new ChildUserIssue();

            $userIssue->setIssue($issue);
            if ($issue->isUsersLoaded()) {
                //remove the back reference if available
                $issue->getUsers()->removeObject($this);
            }

            $userIssue->setUser($this);
            $this->removeUserIssue(clone $userIssue);
            $userIssue->clear();

            $this->collIssues->remove($this->collIssues->search($issue));

            if (null === $this->issuesScheduledForDeletion) {
                $this->issuesScheduledForDeletion = clone $this->collIssues;
                $this->issuesScheduledForDeletion->clear();
            }

            $this->issuesScheduledForDeletion->push($issue);
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
        $this->login = null;
        $this->password = null;
        $this->email = null;
        $this->auth_key = null;
        $this->last_seen_on = null;
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
            if ($this->collUserSeries) {
                foreach ($this->collUserSeries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserIssues) {
                foreach ($this->collUserIssues as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSeries) {
                foreach ($this->collSeries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIssues) {
                foreach ($this->collIssues as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUserSeries = null;
        $this->collUserIssues = null;
        $this->collSeries = null;
        $this->collIssues = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
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
