<?php


/**
 * Base class that represents a query for the 'comics_user' table.
 *
 *
 *
 * @method UserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserQuery orderByLogin($order = Criteria::ASC) Order by the login column
 * @method UserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method UserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method UserQuery orderByAuthKey($order = Criteria::ASC) Order by the auth_key column
 * @method UserQuery orderByLastSeenOn($order = Criteria::ASC) Order by the last_seen_on column
 *
 * @method UserQuery groupById() Group by the id column
 * @method UserQuery groupByLogin() Group by the login column
 * @method UserQuery groupByPassword() Group by the password column
 * @method UserQuery groupByEmail() Group by the email column
 * @method UserQuery groupByAuthKey() Group by the auth_key column
 * @method UserQuery groupByLastSeenOn() Group by the last_seen_on column
 *
 * @method UserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserQuery leftJoinUserSerie($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSerie relation
 * @method UserQuery rightJoinUserSerie($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSerie relation
 * @method UserQuery innerJoinUserSerie($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSerie relation
 *
 * @method UserQuery leftJoinUserIssue($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserIssue relation
 * @method UserQuery rightJoinUserIssue($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserIssue relation
 * @method UserQuery innerJoinUserIssue($relationAlias = null) Adds a INNER JOIN clause to the query using the UserIssue relation
 *
 * @method User findOne(PropelPDO $con = null) Return the first User matching the query
 * @method User findOneOrCreate(PropelPDO $con = null) Return the first User matching the query, or a new User object populated from the query conditions when no match is found
 *
 * @method User findOneByLogin(string $login) Return the first User filtered by the login column
 * @method User findOneByPassword(string $password) Return the first User filtered by the password column
 * @method User findOneByEmail(string $email) Return the first User filtered by the email column
 * @method User findOneByAuthKey(string $auth_key) Return the first User filtered by the auth_key column
 * @method User findOneByLastSeenOn(string $last_seen_on) Return the first User filtered by the last_seen_on column
 *
 * @method array findById(int $id) Return User objects filtered by the id column
 * @method array findByLogin(string $login) Return User objects filtered by the login column
 * @method array findByPassword(string $password) Return User objects filtered by the password column
 * @method array findByEmail(string $email) Return User objects filtered by the email column
 * @method array findByAuthKey(string $auth_key) Return User objects filtered by the auth_key column
 * @method array findByLastSeenOn(string $last_seen_on) Return User objects filtered by the last_seen_on column
 *
 * @package    propel.generator.comicslist.om
 */
abstract class BaseUserQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'comicslist';
        }
        if (null === $modelName) {
            $modelName = 'User';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   UserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuery) {
            return $criteria;
        }
        $query = new UserQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   User|User[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 User A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 User A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `login`, `password`, `email`, `auth_key`, `last_seen_on` FROM `comics_user` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new User();
            $obj->hydrate($row);
            UserPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return User|User[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|User[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the login column
     *
     * Example usage:
     * <code>
     * $query->filterByLogin('fooValue');   // WHERE login = 'fooValue'
     * $query->filterByLogin('%fooValue%'); // WHERE login LIKE '%fooValue%'
     * </code>
     *
     * @param     string $login The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLogin($login = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($login)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $login)) {
                $login = str_replace('*', '%', $login);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::LOGIN, $login, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the auth_key column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthKey('fooValue');   // WHERE auth_key = 'fooValue'
     * $query->filterByAuthKey('%fooValue%'); // WHERE auth_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $authKey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByAuthKey($authKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authKey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $authKey)) {
                $authKey = str_replace('*', '%', $authKey);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserPeer::AUTH_KEY, $authKey, $comparison);
    }

    /**
     * Filter the query on the last_seen_on column
     *
     * Example usage:
     * <code>
     * $query->filterByLastSeenOn('2011-03-14'); // WHERE last_seen_on = '2011-03-14'
     * $query->filterByLastSeenOn('now'); // WHERE last_seen_on = '2011-03-14'
     * $query->filterByLastSeenOn(array('max' => 'yesterday')); // WHERE last_seen_on > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastSeenOn The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function filterByLastSeenOn($lastSeenOn = null, $comparison = null)
    {
        if (is_array($lastSeenOn)) {
            $useMinMax = false;
            if (isset($lastSeenOn['min'])) {
                $this->addUsingAlias(UserPeer::LAST_SEEN_ON, $lastSeenOn['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastSeenOn['max'])) {
                $this->addUsingAlias(UserPeer::LAST_SEEN_ON, $lastSeenOn['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserPeer::LAST_SEEN_ON, $lastSeenOn, $comparison);
    }

    /**
     * Filter the query by a related UserSerie object
     *
     * @param   UserSerie|PropelObjectCollection $userSerie  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserSerie($userSerie, $comparison = null)
    {
        if ($userSerie instanceof UserSerie) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userSerie->getUserId(), $comparison);
        } elseif ($userSerie instanceof PropelObjectCollection) {
            return $this
                ->useUserSerieQuery()
                ->filterByPrimaryKeys($userSerie->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserSerie() only accepts arguments of type UserSerie or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserSerie relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserSerie($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserSerie');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserSerie');
        }

        return $this;
    }

    /**
     * Use the UserSerie relation UserSerie object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   UserSerieQuery A secondary query class using the current class as primary query
     */
    public function useUserSerieQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserSerie($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserSerie', 'UserSerieQuery');
    }

    /**
     * Filter the query by a related UserIssue object
     *
     * @param   UserIssue|PropelObjectCollection $userIssue  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 UserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserIssue($userIssue, $comparison = null)
    {
        if ($userIssue instanceof UserIssue) {
            return $this
                ->addUsingAlias(UserPeer::ID, $userIssue->getUserId(), $comparison);
        } elseif ($userIssue instanceof PropelObjectCollection) {
            return $this
                ->useUserIssueQuery()
                ->filterByPrimaryKeys($userIssue->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserIssue() only accepts arguments of type UserIssue or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserIssue relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function joinUserIssue($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserIssue');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserIssue');
        }

        return $this;
    }

    /**
     * Use the UserIssue relation UserIssue object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   UserIssueQuery A secondary query class using the current class as primary query
     */
    public function useUserIssueQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserIssue($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserIssue', 'UserIssueQuery');
    }

    /**
     * Filter the query by a related Serie object
     * using the comics_user_serie table as cross reference
     *
     * @param   Serie $serie the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterBySerie($serie, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserSerieQuery()
            ->filterBySerie($serie, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Issue object
     * using the comics_user_issue table as cross reference
     *
     * @param   Issue $issue the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuery The current query, for fluid interface
     */
    public function filterByIssue($issue, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserIssueQuery()
            ->filterByIssue($issue, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   User $user Object to remove from the list of results
     *
     * @return UserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserPeer::ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
