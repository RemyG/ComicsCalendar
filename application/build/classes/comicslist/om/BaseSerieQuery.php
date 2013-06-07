<?php


/**
 * Base class that represents a query for the 'comics_serie' table.
 *
 *
 *
 * @method SerieQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SerieQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method SerieQuery orderByCvId($order = Criteria::ASC) Order by the cv_id column
 * @method SerieQuery orderByCvUrl($order = Criteria::ASC) Order by the cv_url column
 * @method SerieQuery orderByAddedOn($order = Criteria::ASC) Order by the added_on column
 *
 * @method SerieQuery groupById() Group by the id column
 * @method SerieQuery groupByTitle() Group by the title column
 * @method SerieQuery groupByCvId() Group by the cv_id column
 * @method SerieQuery groupByCvUrl() Group by the cv_url column
 * @method SerieQuery groupByAddedOn() Group by the added_on column
 *
 * @method SerieQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SerieQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SerieQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SerieQuery leftJoinIssue($relationAlias = null) Adds a LEFT JOIN clause to the query using the Issue relation
 * @method SerieQuery rightJoinIssue($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Issue relation
 * @method SerieQuery innerJoinIssue($relationAlias = null) Adds a INNER JOIN clause to the query using the Issue relation
 *
 * @method SerieQuery leftJoinUserSerie($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSerie relation
 * @method SerieQuery rightJoinUserSerie($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSerie relation
 * @method SerieQuery innerJoinUserSerie($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSerie relation
 *
 * @method Serie findOne(PropelPDO $con = null) Return the first Serie matching the query
 * @method Serie findOneOrCreate(PropelPDO $con = null) Return the first Serie matching the query, or a new Serie object populated from the query conditions when no match is found
 *
 * @method Serie findOneByTitle(string $title) Return the first Serie filtered by the title column
 * @method Serie findOneByCvId(string $cv_id) Return the first Serie filtered by the cv_id column
 * @method Serie findOneByCvUrl(string $cv_url) Return the first Serie filtered by the cv_url column
 * @method Serie findOneByAddedOn(string $added_on) Return the first Serie filtered by the added_on column
 *
 * @method array findById(int $id) Return Serie objects filtered by the id column
 * @method array findByTitle(string $title) Return Serie objects filtered by the title column
 * @method array findByCvId(string $cv_id) Return Serie objects filtered by the cv_id column
 * @method array findByCvUrl(string $cv_url) Return Serie objects filtered by the cv_url column
 * @method array findByAddedOn(string $added_on) Return Serie objects filtered by the added_on column
 *
 * @package    propel.generator.comicslist.om
 */
abstract class BaseSerieQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSerieQuery object.
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
            $modelName = 'Serie';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SerieQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SerieQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SerieQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SerieQuery) {
            return $criteria;
        }
        $query = new SerieQuery(null, null, $modelAlias);

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
     * @return   Serie|Serie[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SeriePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SeriePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Serie A model object, or null if the key is not found
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
     * @return                 Serie A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `cv_id`, `cv_url`, `added_on` FROM `comics_serie` WHERE `id` = :p0';
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
            $obj = new Serie();
            $obj->hydrate($row);
            SeriePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Serie|Serie[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Serie[]|mixed the list of results, formatted by the current formatter
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
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SeriePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SeriePeer::ID, $keys, Criteria::IN);
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
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SeriePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SeriePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeriePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the cv_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCvId('fooValue');   // WHERE cv_id = 'fooValue'
     * $query->filterByCvId('%fooValue%'); // WHERE cv_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cvId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterByCvId($cvId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cvId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cvId)) {
                $cvId = str_replace('*', '%', $cvId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeriePeer::CV_ID, $cvId, $comparison);
    }

    /**
     * Filter the query on the cv_url column
     *
     * Example usage:
     * <code>
     * $query->filterByCvUrl('fooValue');   // WHERE cv_url = 'fooValue'
     * $query->filterByCvUrl('%fooValue%'); // WHERE cv_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cvUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterByCvUrl($cvUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cvUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $cvUrl)) {
                $cvUrl = str_replace('*', '%', $cvUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeriePeer::CV_URL, $cvUrl, $comparison);
    }

    /**
     * Filter the query on the added_on column
     *
     * Example usage:
     * <code>
     * $query->filterByAddedOn('2011-03-14'); // WHERE added_on = '2011-03-14'
     * $query->filterByAddedOn('now'); // WHERE added_on = '2011-03-14'
     * $query->filterByAddedOn(array('max' => 'yesterday')); // WHERE added_on > '2011-03-13'
     * </code>
     *
     * @param     mixed $addedOn The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function filterByAddedOn($addedOn = null, $comparison = null)
    {
        if (is_array($addedOn)) {
            $useMinMax = false;
            if (isset($addedOn['min'])) {
                $this->addUsingAlias(SeriePeer::ADDED_ON, $addedOn['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($addedOn['max'])) {
                $this->addUsingAlias(SeriePeer::ADDED_ON, $addedOn['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeriePeer::ADDED_ON, $addedOn, $comparison);
    }

    /**
     * Filter the query by a related Issue object
     *
     * @param   Issue|PropelObjectCollection $issue  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SerieQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIssue($issue, $comparison = null)
    {
        if ($issue instanceof Issue) {
            return $this
                ->addUsingAlias(SeriePeer::ID, $issue->getSerieId(), $comparison);
        } elseif ($issue instanceof PropelObjectCollection) {
            return $this
                ->useIssueQuery()
                ->filterByPrimaryKeys($issue->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIssue() only accepts arguments of type Issue or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Issue relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function joinIssue($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Issue');

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
            $this->addJoinObject($join, 'Issue');
        }

        return $this;
    }

    /**
     * Use the Issue relation Issue object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   IssueQuery A secondary query class using the current class as primary query
     */
    public function useIssueQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIssue($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Issue', 'IssueQuery');
    }

    /**
     * Filter the query by a related UserSerie object
     *
     * @param   UserSerie|PropelObjectCollection $userSerie  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SerieQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserSerie($userSerie, $comparison = null)
    {
        if ($userSerie instanceof UserSerie) {
            return $this
                ->addUsingAlias(SeriePeer::ID, $userSerie->getSerieId(), $comparison);
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
     * @return SerieQuery The current query, for fluid interface
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
     * Filter the query by a related User object
     * using the comics_user_serie table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   SerieQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserSerieQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Serie $serie Object to remove from the list of results
     *
     * @return SerieQuery The current query, for fluid interface
     */
    public function prune($serie = null)
    {
        if ($serie) {
            $this->addUsingAlias(SeriePeer::ID, $serie->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
