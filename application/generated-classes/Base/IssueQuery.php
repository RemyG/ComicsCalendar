<?php

namespace Base;

use \Issue as ChildIssue;
use \IssueQuery as ChildIssueQuery;
use \Exception;
use \PDO;
use Map\IssueTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'comics_issue' table.
 *
 *
 *
 * @method     ChildIssueQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildIssueQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildIssueQuery orderByIssueNumber($order = Criteria::ASC) Order by the issue_number column
 * @method     ChildIssueQuery orderBySerieId($order = Criteria::ASC) Order by the serie_id column
 * @method     ChildIssueQuery orderByPubDate($order = Criteria::ASC) Order by the pub_date column
 * @method     ChildIssueQuery orderByCvId($order = Criteria::ASC) Order by the cv_id column
 * @method     ChildIssueQuery orderByCvUrl($order = Criteria::ASC) Order by the cv_url column
 *
 * @method     ChildIssueQuery groupById() Group by the id column
 * @method     ChildIssueQuery groupByTitle() Group by the title column
 * @method     ChildIssueQuery groupByIssueNumber() Group by the issue_number column
 * @method     ChildIssueQuery groupBySerieId() Group by the serie_id column
 * @method     ChildIssueQuery groupByPubDate() Group by the pub_date column
 * @method     ChildIssueQuery groupByCvId() Group by the cv_id column
 * @method     ChildIssueQuery groupByCvUrl() Group by the cv_url column
 *
 * @method     ChildIssueQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildIssueQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildIssueQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildIssueQuery leftJoinSerie($relationAlias = null) Adds a LEFT JOIN clause to the query using the Serie relation
 * @method     ChildIssueQuery rightJoinSerie($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Serie relation
 * @method     ChildIssueQuery innerJoinSerie($relationAlias = null) Adds a INNER JOIN clause to the query using the Serie relation
 *
 * @method     ChildIssueQuery leftJoinUserIssue($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserIssue relation
 * @method     ChildIssueQuery rightJoinUserIssue($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserIssue relation
 * @method     ChildIssueQuery innerJoinUserIssue($relationAlias = null) Adds a INNER JOIN clause to the query using the UserIssue relation
 *
 * @method     \SerieQuery|\UserIssueQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildIssue findOne(ConnectionInterface $con = null) Return the first ChildIssue matching the query
 * @method     ChildIssue findOneOrCreate(ConnectionInterface $con = null) Return the first ChildIssue matching the query, or a new ChildIssue object populated from the query conditions when no match is found
 *
 * @method     ChildIssue findOneById(int $id) Return the first ChildIssue filtered by the id column
 * @method     ChildIssue findOneByTitle(string $title) Return the first ChildIssue filtered by the title column
 * @method     ChildIssue findOneByIssueNumber(string $issue_number) Return the first ChildIssue filtered by the issue_number column
 * @method     ChildIssue findOneBySerieId(int $serie_id) Return the first ChildIssue filtered by the serie_id column
 * @method     ChildIssue findOneByPubDate(string $pub_date) Return the first ChildIssue filtered by the pub_date column
 * @method     ChildIssue findOneByCvId(string $cv_id) Return the first ChildIssue filtered by the cv_id column
 * @method     ChildIssue findOneByCvUrl(string $cv_url) Return the first ChildIssue filtered by the cv_url column *

 * @method     ChildIssue requirePk($key, ConnectionInterface $con = null) Return the ChildIssue by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOne(ConnectionInterface $con = null) Return the first ChildIssue matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildIssue requireOneById(int $id) Return the first ChildIssue filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOneByTitle(string $title) Return the first ChildIssue filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOneByIssueNumber(string $issue_number) Return the first ChildIssue filtered by the issue_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOneBySerieId(int $serie_id) Return the first ChildIssue filtered by the serie_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOneByPubDate(string $pub_date) Return the first ChildIssue filtered by the pub_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOneByCvId(string $cv_id) Return the first ChildIssue filtered by the cv_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIssue requireOneByCvUrl(string $cv_url) Return the first ChildIssue filtered by the cv_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildIssue[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildIssue objects based on current ModelCriteria
 * @method     ChildIssue[]|ObjectCollection findById(int $id) Return ChildIssue objects filtered by the id column
 * @method     ChildIssue[]|ObjectCollection findByTitle(string $title) Return ChildIssue objects filtered by the title column
 * @method     ChildIssue[]|ObjectCollection findByIssueNumber(string $issue_number) Return ChildIssue objects filtered by the issue_number column
 * @method     ChildIssue[]|ObjectCollection findBySerieId(int $serie_id) Return ChildIssue objects filtered by the serie_id column
 * @method     ChildIssue[]|ObjectCollection findByPubDate(string $pub_date) Return ChildIssue objects filtered by the pub_date column
 * @method     ChildIssue[]|ObjectCollection findByCvId(string $cv_id) Return ChildIssue objects filtered by the cv_id column
 * @method     ChildIssue[]|ObjectCollection findByCvUrl(string $cv_url) Return ChildIssue objects filtered by the cv_url column
 * @method     ChildIssue[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class IssueQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\IssueQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'comicslist', $modelName = '\\Issue', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildIssueQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildIssueQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildIssueQuery) {
            return $criteria;
        }
        $query = new ChildIssueQuery();
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
     * @return ChildIssue|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IssueTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(IssueTableMap::DATABASE_NAME);
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
     * @return ChildIssue A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, issue_number, serie_id, pub_date, cv_id, cv_url FROM comics_issue WHERE id = :p0';
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
            /** @var ChildIssue $obj */
            $obj = new ChildIssue();
            $obj->hydrate($row);
            IssueTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildIssue|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IssueTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IssueTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IssueTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IssueTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IssueTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildIssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssueTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the issue_number column
     *
     * Example usage:
     * <code>
     * $query->filterByIssueNumber('fooValue');   // WHERE issue_number = 'fooValue'
     * $query->filterByIssueNumber('%fooValue%'); // WHERE issue_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $issueNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function filterByIssueNumber($issueNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($issueNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $issueNumber)) {
                $issueNumber = str_replace('*', '%', $issueNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IssueTableMap::COL_ISSUE_NUMBER, $issueNumber, $comparison);
    }

    /**
     * Filter the query on the serie_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySerieId(1234); // WHERE serie_id = 1234
     * $query->filterBySerieId(array(12, 34)); // WHERE serie_id IN (12, 34)
     * $query->filterBySerieId(array('min' => 12)); // WHERE serie_id > 12
     * </code>
     *
     * @see       filterBySerie()
     *
     * @param     mixed $serieId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function filterBySerieId($serieId = null, $comparison = null)
    {
        if (is_array($serieId)) {
            $useMinMax = false;
            if (isset($serieId['min'])) {
                $this->addUsingAlias(IssueTableMap::COL_SERIE_ID, $serieId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($serieId['max'])) {
                $this->addUsingAlias(IssueTableMap::COL_SERIE_ID, $serieId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IssueTableMap::COL_SERIE_ID, $serieId, $comparison);
    }

    /**
     * Filter the query on the pub_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPubDate('2011-03-14'); // WHERE pub_date = '2011-03-14'
     * $query->filterByPubDate('now'); // WHERE pub_date = '2011-03-14'
     * $query->filterByPubDate(array('max' => 'yesterday')); // WHERE pub_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $pubDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function filterByPubDate($pubDate = null, $comparison = null)
    {
        if (is_array($pubDate)) {
            $useMinMax = false;
            if (isset($pubDate['min'])) {
                $this->addUsingAlias(IssueTableMap::COL_PUB_DATE, $pubDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pubDate['max'])) {
                $this->addUsingAlias(IssueTableMap::COL_PUB_DATE, $pubDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IssueTableMap::COL_PUB_DATE, $pubDate, $comparison);
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
     * @return $this|ChildIssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssueTableMap::COL_CV_ID, $cvId, $comparison);
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
     * @return $this|ChildIssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssueTableMap::COL_CV_URL, $cvUrl, $comparison);
    }

    /**
     * Filter the query by a related \Serie object
     *
     * @param \Serie|ObjectCollection $serie The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildIssueQuery The current query, for fluid interface
     */
    public function filterBySerie($serie, $comparison = null)
    {
        if ($serie instanceof \Serie) {
            return $this
                ->addUsingAlias(IssueTableMap::COL_SERIE_ID, $serie->getId(), $comparison);
        } elseif ($serie instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IssueTableMap::COL_SERIE_ID, $serie->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySerie() only accepts arguments of type \Serie or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Serie relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function joinSerie($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Serie');

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
            $this->addJoinObject($join, 'Serie');
        }

        return $this;
    }

    /**
     * Use the Serie relation Serie object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SerieQuery A secondary query class using the current class as primary query
     */
    public function useSerieQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSerie($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Serie', '\SerieQuery');
    }

    /**
     * Filter the query by a related \UserIssue object
     *
     * @param \UserIssue|ObjectCollection $userIssue  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIssueQuery The current query, for fluid interface
     */
    public function filterByUserIssue($userIssue, $comparison = null)
    {
        if ($userIssue instanceof \UserIssue) {
            return $this
                ->addUsingAlias(IssueTableMap::COL_ID, $userIssue->getIssueId(), $comparison);
        } elseif ($userIssue instanceof ObjectCollection) {
            return $this
                ->useUserIssueQuery()
                ->filterByPrimaryKeys($userIssue->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserIssue() only accepts arguments of type \UserIssue or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserIssue relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
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
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \UserIssueQuery A secondary query class using the current class as primary query
     */
    public function useUserIssueQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserIssue($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserIssue', '\UserIssueQuery');
    }

    /**
     * Filter the query by a related User object
     * using the comics_user_issue table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildIssueQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserIssueQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildIssue $issue Object to remove from the list of results
     *
     * @return $this|ChildIssueQuery The current query, for fluid interface
     */
    public function prune($issue = null)
    {
        if ($issue) {
            $this->addUsingAlias(IssueTableMap::COL_ID, $issue->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the comics_issue table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IssueTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            IssueTableMap::clearInstancePool();
            IssueTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(IssueTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(IssueTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            IssueTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            IssueTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // IssueQuery
