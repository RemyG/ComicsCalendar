<?php

namespace Base;

use \Serie as ChildSerie;
use \SerieQuery as ChildSerieQuery;
use \Exception;
use \PDO;
use Map\SerieTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'comics_serie' table.
 *
 *
 *
 * @method     ChildSerieQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSerieQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildSerieQuery orderByCvId($order = Criteria::ASC) Order by the cv_id column
 * @method     ChildSerieQuery orderByCvUrl($order = Criteria::ASC) Order by the cv_url column
 * @method     ChildSerieQuery orderByAddedOn($order = Criteria::ASC) Order by the added_on column
 *
 * @method     ChildSerieQuery groupById() Group by the id column
 * @method     ChildSerieQuery groupByTitle() Group by the title column
 * @method     ChildSerieQuery groupByCvId() Group by the cv_id column
 * @method     ChildSerieQuery groupByCvUrl() Group by the cv_url column
 * @method     ChildSerieQuery groupByAddedOn() Group by the added_on column
 *
 * @method     ChildSerieQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSerieQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSerieQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSerieQuery leftJoinIssue($relationAlias = null) Adds a LEFT JOIN clause to the query using the Issue relation
 * @method     ChildSerieQuery rightJoinIssue($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Issue relation
 * @method     ChildSerieQuery innerJoinIssue($relationAlias = null) Adds a INNER JOIN clause to the query using the Issue relation
 *
 * @method     ChildSerieQuery leftJoinUserSerie($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserSerie relation
 * @method     ChildSerieQuery rightJoinUserSerie($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserSerie relation
 * @method     ChildSerieQuery innerJoinUserSerie($relationAlias = null) Adds a INNER JOIN clause to the query using the UserSerie relation
 *
 * @method     \IssueQuery|\UserSerieQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSerie findOne(ConnectionInterface $con = null) Return the first ChildSerie matching the query
 * @method     ChildSerie findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSerie matching the query, or a new ChildSerie object populated from the query conditions when no match is found
 *
 * @method     ChildSerie findOneById(int $id) Return the first ChildSerie filtered by the id column
 * @method     ChildSerie findOneByTitle(string $title) Return the first ChildSerie filtered by the title column
 * @method     ChildSerie findOneByCvId(string $cv_id) Return the first ChildSerie filtered by the cv_id column
 * @method     ChildSerie findOneByCvUrl(string $cv_url) Return the first ChildSerie filtered by the cv_url column
 * @method     ChildSerie findOneByAddedOn(string $added_on) Return the first ChildSerie filtered by the added_on column *

 * @method     ChildSerie requirePk($key, ConnectionInterface $con = null) Return the ChildSerie by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSerie requireOne(ConnectionInterface $con = null) Return the first ChildSerie matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSerie requireOneById(int $id) Return the first ChildSerie filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSerie requireOneByTitle(string $title) Return the first ChildSerie filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSerie requireOneByCvId(string $cv_id) Return the first ChildSerie filtered by the cv_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSerie requireOneByCvUrl(string $cv_url) Return the first ChildSerie filtered by the cv_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSerie requireOneByAddedOn(string $added_on) Return the first ChildSerie filtered by the added_on column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSerie[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSerie objects based on current ModelCriteria
 * @method     ChildSerie[]|ObjectCollection findById(int $id) Return ChildSerie objects filtered by the id column
 * @method     ChildSerie[]|ObjectCollection findByTitle(string $title) Return ChildSerie objects filtered by the title column
 * @method     ChildSerie[]|ObjectCollection findByCvId(string $cv_id) Return ChildSerie objects filtered by the cv_id column
 * @method     ChildSerie[]|ObjectCollection findByCvUrl(string $cv_url) Return ChildSerie objects filtered by the cv_url column
 * @method     ChildSerie[]|ObjectCollection findByAddedOn(string $added_on) Return ChildSerie objects filtered by the added_on column
 * @method     ChildSerie[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SerieQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\SerieQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'comicslist', $modelName = '\\Serie', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSerieQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSerieQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSerieQuery) {
            return $criteria;
        }
        $query = new ChildSerieQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSerie|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SerieTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SerieTableMap::DATABASE_NAME);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSerie A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, cv_id, cv_url, added_on FROM comics_serie WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildSerie $obj */
            $obj = new ChildSerie();
            $obj->hydrate($row);
            SerieTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSerie|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildSerieQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SerieTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSerieQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SerieTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSerieQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SerieTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SerieTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SerieTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildSerieQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SerieTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildSerieQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SerieTableMap::COL_CV_ID, $cvId, $comparison);
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
     * @return $this|ChildSerieQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SerieTableMap::COL_CV_URL, $cvUrl, $comparison);
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
     * @return $this|ChildSerieQuery The current query, for fluid interface
     */
    public function filterByAddedOn($addedOn = null, $comparison = null)
    {
        if (is_array($addedOn)) {
            $useMinMax = false;
            if (isset($addedOn['min'])) {
                $this->addUsingAlias(SerieTableMap::COL_ADDED_ON, $addedOn['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($addedOn['max'])) {
                $this->addUsingAlias(SerieTableMap::COL_ADDED_ON, $addedOn['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SerieTableMap::COL_ADDED_ON, $addedOn, $comparison);
    }

    /**
     * Filter the query by a related \Issue object
     *
     * @param \Issue|ObjectCollection $issue  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSerieQuery The current query, for fluid interface
     */
    public function filterByIssue($issue, $comparison = null)
    {
        if ($issue instanceof \Issue) {
            return $this
                ->addUsingAlias(SerieTableMap::COL_ID, $issue->getSerieId(), $comparison);
        } elseif ($issue instanceof ObjectCollection) {
            return $this
                ->useIssueQuery()
                ->filterByPrimaryKeys($issue->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIssue() only accepts arguments of type \Issue or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Issue relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSerieQuery The current query, for fluid interface
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \IssueQuery A secondary query class using the current class as primary query
     */
    public function useIssueQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIssue($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Issue', '\IssueQuery');
    }

    /**
     * Filter the query by a related \UserSerie object
     *
     * @param \UserSerie|ObjectCollection $userSerie  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSerieQuery The current query, for fluid interface
     */
    public function filterByUserSerie($userSerie, $comparison = null)
    {
        if ($userSerie instanceof \UserSerie) {
            return $this
                ->addUsingAlias(SerieTableMap::COL_ID, $userSerie->getSerieId(), $comparison);
        } elseif ($userSerie instanceof ObjectCollection) {
            return $this
                ->useUserSerieQuery()
                ->filterByPrimaryKeys($userSerie->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserSerie() only accepts arguments of type \UserSerie or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserSerie relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSerieQuery The current query, for fluid interface
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserSerieQuery A secondary query class using the current class as primary query
     */
    public function useUserSerieQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserSerie($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserSerie', '\UserSerieQuery');
    }

    /**
     * Filter the query by a related User object
     * using the comics_user_serie table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSerieQuery The current query, for fluid interface
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
     * @param   ChildSerie $serie Object to remove from the list of results
     *
     * @return $this|ChildSerieQuery The current query, for fluid interface
     */
    public function prune($serie = null)
    {
        if ($serie) {
            $this->addUsingAlias(SerieTableMap::COL_ID, $serie->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the comics_serie table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SerieTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SerieTableMap::clearInstancePool();
            SerieTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SerieTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SerieTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SerieTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SerieTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SerieQuery
