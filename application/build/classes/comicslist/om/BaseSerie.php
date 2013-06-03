<?php


/**
 * Base class that represents a row from the 'comics_serie' table.
 *
 *
 *
 * @package    propel.generator.comicslist.om
 */
abstract class BaseSerie extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'SeriePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        SeriePeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

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
     * @var        PropelObjectCollection|Issue[] Collection to store aggregation of Issue objects.
     */
    protected $collIssues;
    protected $collIssuesPartial;

    /**
     * @var        PropelObjectCollection|UserSerie[] Collection to store aggregation of UserSerie objects.
     */
    protected $collUserSeries;
    protected $collUserSeriesPartial;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collUsers;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $issuesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userSeriesScheduledForDeletion = null;

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
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Serie The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = SeriePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param  string $v new value
     * @return Serie The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = SeriePeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [cv_id] column.
     *
     * @param  string $v new value
     * @return Serie The current object (for fluent API support)
     */
    public function setCvId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->cv_id !== $v) {
            $this->cv_id = $v;
            $this->modifiedColumns[] = SeriePeer::CV_ID;
        }


        return $this;
    } // setCvId()

    /**
     * Set the value of [cv_url] column.
     *
     * @param  string $v new value
     * @return Serie The current object (for fluent API support)
     */
    public function setCvUrl($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->cv_url !== $v) {
            $this->cv_url = $v;
            $this->modifiedColumns[] = SeriePeer::CV_URL;
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
        // otherwise, everything was equal, so return true
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
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->cv_id = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->cv_url = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 4; // 4 = SeriePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Serie object", $e);
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
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(SeriePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = SeriePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collIssues = null;

            $this->collUserSeries = null;

            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(SeriePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = SerieQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(SeriePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
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
                SeriePeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->usersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    UserSerieQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->usersScheduledForDeletion = null;
                }

                foreach ($this->getUsers() as $user) {
                    if ($user->isModified()) {
                        $user->save($con);
                    }
                }
            } elseif ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if ($user->isModified()) {
                        $user->save($con);
                    }
                }
            }

            if ($this->issuesScheduledForDeletion !== null) {
                if (!$this->issuesScheduledForDeletion->isEmpty()) {
                    IssueQuery::create()
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
                    UserSerieQuery::create()
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
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = SeriePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SeriePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SeriePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(SeriePeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(SeriePeer::CV_ID)) {
            $modifiedColumns[':p' . $index++]  = '`cv_id`';
        }
        if ($this->isColumnModified(SeriePeer::CV_URL)) {
            $modifiedColumns[':p' . $index++]  = '`cv_url`';
        }

        $sql = sprintf(
            'INSERT INTO `comics_serie` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`cv_id`':
                        $stmt->bindValue($identifier, $this->cv_id, PDO::PARAM_STR);
                        break;
                    case '`cv_url`':
                        $stmt->bindValue($identifier, $this->cv_url, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = SeriePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collIssues !== null) {
                    foreach ($this->collIssues as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserSeries !== null) {
                    foreach ($this->collUserSeries as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = SeriePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
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
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Serie'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Serie'][$this->getPrimaryKey()] = true;
        $keys = SeriePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getCvId(),
            $keys[3] => $this->getCvUrl(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach($virtualColumns as $key => $virtualColumn)
        {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collIssues) {
                $result['Issues'] = $this->collIssues->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserSeries) {
                $result['UserSeries'] = $this->collUserSeries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = SeriePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
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
        } // switch()
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
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = SeriePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCvId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCvUrl($arr[$keys[3]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SeriePeer::DATABASE_NAME);

        if ($this->isColumnModified(SeriePeer::ID)) $criteria->add(SeriePeer::ID, $this->id);
        if ($this->isColumnModified(SeriePeer::TITLE)) $criteria->add(SeriePeer::TITLE, $this->title);
        if ($this->isColumnModified(SeriePeer::CV_ID)) $criteria->add(SeriePeer::CV_ID, $this->cv_id);
        if ($this->isColumnModified(SeriePeer::CV_URL)) $criteria->add(SeriePeer::CV_URL, $this->cv_url);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(SeriePeer::DATABASE_NAME);
        $criteria->add(SeriePeer::ID, $this->id);

        return $criteria;
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
     * @param  int $key Primary key.
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
     * @param object $copyObj An object of Serie (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setCvId($this->getCvId());
        $copyObj->setCvUrl($this->getCvUrl());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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

            //unflag object copy
            $this->startCopy = false;
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
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Serie Clone of current object.
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
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return SeriePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new SeriePeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Issue' == $relationName) {
            $this->initIssues();
        }
        if ('UserSerie' == $relationName) {
            $this->initUserSeries();
        }
    }

    /**
     * Clears out the collIssues collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Serie The current object (for fluent API support)
     * @see        addIssues()
     */
    public function clearIssues()
    {
        $this->collIssues = null; // important to set this to null since that means it is uninitialized
        $this->collIssuesPartial = null;

        return $this;
    }

    /**
     * reset is the collIssues collection loaded partially
     *
     * @return void
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
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIssues($overrideExisting = true)
    {
        if (null !== $this->collIssues && !$overrideExisting) {
            return;
        }
        $this->collIssues = new PropelObjectCollection();
        $this->collIssues->setModel('Issue');
    }

    /**
     * Gets an array of Issue objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Serie is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Issue[] List of Issue objects
     * @throws PropelException
     */
    public function getIssues($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIssuesPartial && !$this->isNew();
        if (null === $this->collIssues || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIssues) {
                // return empty collection
                $this->initIssues();
            } else {
                $collIssues = IssueQuery::create(null, $criteria)
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

                    $collIssues->getInternalIterator()->rewind();

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
     * Sets a collection of Issue objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $issues A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Serie The current object (for fluent API support)
     */
    public function setIssues(PropelCollection $issues, PropelPDO $con = null)
    {
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
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Issue objects.
     * @throws PropelException
     */
    public function countIssues(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIssuesPartial && !$this->isNew();
        if (null === $this->collIssues || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIssues) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIssues());
            }
            $query = IssueQuery::create(null, $criteria);
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
     * Method called to associate a Issue object to this object
     * through the Issue foreign key attribute.
     *
     * @param    Issue $l Issue
     * @return Serie The current object (for fluent API support)
     */
    public function addIssue(Issue $l)
    {
        if ($this->collIssues === null) {
            $this->initIssues();
            $this->collIssuesPartial = true;
        }
        if (!in_array($l, $this->collIssues->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIssue($l);
        }

        return $this;
    }

    /**
     * @param	Issue $issue The issue object to add.
     */
    protected function doAddIssue($issue)
    {
        $this->collIssues[]= $issue;
        $issue->setSerie($this);
    }

    /**
     * @param	Issue $issue The issue object to remove.
     * @return Serie The current object (for fluent API support)
     */
    public function removeIssue($issue)
    {
        if ($this->getIssues()->contains($issue)) {
            $this->collIssues->remove($this->collIssues->search($issue));
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
     * @return Serie The current object (for fluent API support)
     * @see        addUserSeries()
     */
    public function clearUserSeries()
    {
        $this->collUserSeries = null; // important to set this to null since that means it is uninitialized
        $this->collUserSeriesPartial = null;

        return $this;
    }

    /**
     * reset is the collUserSeries collection loaded partially
     *
     * @return void
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
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserSeries($overrideExisting = true)
    {
        if (null !== $this->collUserSeries && !$overrideExisting) {
            return;
        }
        $this->collUserSeries = new PropelObjectCollection();
        $this->collUserSeries->setModel('UserSerie');
    }

    /**
     * Gets an array of UserSerie objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Serie is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserSerie[] List of UserSerie objects
     * @throws PropelException
     */
    public function getUserSeries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserSeriesPartial && !$this->isNew();
        if (null === $this->collUserSeries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserSeries) {
                // return empty collection
                $this->initUserSeries();
            } else {
                $collUserSeries = UserSerieQuery::create(null, $criteria)
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

                    $collUserSeries->getInternalIterator()->rewind();

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
     * Sets a collection of UserSerie objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userSeries A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Serie The current object (for fluent API support)
     */
    public function setUserSeries(PropelCollection $userSeries, PropelPDO $con = null)
    {
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
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserSerie objects.
     * @throws PropelException
     */
    public function countUserSeries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserSeriesPartial && !$this->isNew();
        if (null === $this->collUserSeries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserSeries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUserSeries());
            }
            $query = UserSerieQuery::create(null, $criteria);
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
     * Method called to associate a UserSerie object to this object
     * through the UserSerie foreign key attribute.
     *
     * @param    UserSerie $l UserSerie
     * @return Serie The current object (for fluent API support)
     */
    public function addUserSerie(UserSerie $l)
    {
        if ($this->collUserSeries === null) {
            $this->initUserSeries();
            $this->collUserSeriesPartial = true;
        }
        if (!in_array($l, $this->collUserSeries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserSerie($l);
        }

        return $this;
    }

    /**
     * @param	UserSerie $userSerie The userSerie object to add.
     */
    protected function doAddUserSerie($userSerie)
    {
        $this->collUserSeries[]= $userSerie;
        $userSerie->setSerie($this);
    }

    /**
     * @param	UserSerie $userSerie The userSerie object to remove.
     * @return Serie The current object (for fluent API support)
     */
    public function removeUserSerie($userSerie)
    {
        if ($this->getUserSeries()->contains($userSerie)) {
            $this->collUserSeries->remove($this->collUserSeries->search($userSerie));
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
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserSerie[] List of UserSerie objects
     */
    public function getUserSeriesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserSerieQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserSeries($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Serie The current object (for fluent API support)
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to null since that means it is uninitialized
        $this->collUsersPartial = null;

        return $this;
    }

    /**
     * Initializes the collUsers collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $this->collUsers = new PropelObjectCollection();
        $this->collUsers->setModel('User');
    }

    /**
     * Gets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Serie is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collUsers) {
                // return empty collection
                $this->initUsers();
            } else {
                $collUsers = UserQuery::create(null, $criteria)
                    ->filterBySerie($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }
                $this->collUsers = $collUsers;
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
     * @param PropelCollection $users A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Serie The current object (for fluent API support)
     */
    public function setUsers(PropelCollection $users, PropelPDO $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $this->usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the comics_user_serie cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {
                $query = UserQuery::create(null, $criteria);
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
     * Associate a User object to this object
     * through the comics_user_serie cross reference table.
     *
     * @param  User $user The UserSerie object to relate
     * @return Serie The current object (for fluent API support)
     */
    public function addUser(User $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }
        if (!$this->collUsers->contains($user)) { // only add it if the **same** object is not already associated
            $this->doAddUser($user);
            $this->collUsers[] = $user;
        }

        return $this;
    }

    /**
     * @param	User $user The user object to add.
     */
    protected function doAddUser($user)
    {
        $userSerie = new UserSerie();
        $userSerie->setUser($user);
        $this->addUserSerie($userSerie);
        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->getSeries()->contains($this)) {
            $foreignCollection = $user->getSeries();
            $foreignCollection[] = $this;
        }
    }

    /**
     * Remove a User object to this object
     * through the comics_user_serie cross reference table.
     *
     * @param User $user The UserSerie object to relate
     * @return Serie The current object (for fluent API support)
     */
    public function removeUser(User $user)
    {
        if ($this->getUsers()->contains($user)) {
            $this->collUsers->remove($this->collUsers->search($user));
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }
            $this->usersScheduledForDeletion[]= $user;
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->cv_id = null;
        $this->cv_url = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collIssues instanceof PropelCollection) {
            $this->collIssues->clearIterator();
        }
        $this->collIssues = null;
        if ($this->collUserSeries instanceof PropelCollection) {
            $this->collUserSeries->clearIterator();
        }
        $this->collUserSeries = null;
        if ($this->collUsers instanceof PropelCollection) {
            $this->collUsers->clearIterator();
        }
        $this->collUsers = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SeriePeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}