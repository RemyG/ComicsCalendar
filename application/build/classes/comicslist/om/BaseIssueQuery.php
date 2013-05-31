<?php


/**
 * Base class that represents a query for the 'comics_issue' table.
 *
 *
 *
 * @method IssueQuery orderById($order = Criteria::ASC) Order by the id column
 * @method IssueQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method IssueQuery orderByIssueNumber($order = Criteria::ASC) Order by the issue_number column
 * @method IssueQuery orderBySerieId($order = Criteria::ASC) Order by the serie_id column
 * @method IssueQuery orderByPubDate($order = Criteria::ASC) Order by the pub_date column
 * @method IssueQuery orderByCvId($order = Criteria::ASC) Order by the cv_id column
 * @method IssueQuery orderByCvUrl($order = Criteria::ASC) Order by the cv_url column
 *
 * @method IssueQuery groupById() Group by the id column
 * @method IssueQuery groupByTitle() Group by the title column
 * @method IssueQuery groupByIssueNumber() Group by the issue_number column
 * @method IssueQuery groupBySerieId() Group by the serie_id column
 * @method IssueQuery groupByPubDate() Group by the pub_date column
 * @method IssueQuery groupByCvId() Group by the cv_id column
 * @method IssueQuery groupByCvUrl() Group by the cv_url column
 *
 * @method IssueQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method IssueQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method IssueQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method IssueQuery leftJoinSerie($relationAlias = null) Adds a LEFT JOIN clause to the query using the Serie relation
 * @method IssueQuery rightJoinSerie($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Serie relation
 * @method IssueQuery innerJoinSerie($relationAlias = null) Adds a INNER JOIN clause to the query using the Serie relation
 *
 * @method Issue findOne(PropelPDO $con = null) Return the first Issue matching the query
 * @method Issue findOneOrCreate(PropelPDO $con = null) Return the first Issue matching the query, or a new Issue object populated from the query conditions when no match is found
 *
 * @method Issue findOneByTitle(string $title) Return the first Issue filtered by the title column
 * @method Issue findOneByIssueNumber(string $issue_number) Return the first Issue filtered by the issue_number column
 * @method Issue findOneBySerieId(int $serie_id) Return the first Issue filtered by the serie_id column
 * @method Issue findOneByPubDate(string $pub_date) Return the first Issue filtered by the pub_date column
 * @method Issue findOneByCvId(string $cv_id) Return the first Issue filtered by the cv_id column
 * @method Issue findOneByCvUrl(string $cv_url) Return the first Issue filtered by the cv_url column
 *
 * @method array findById(int $id) Return Issue objects filtered by the id column
 * @method array findByTitle(string $title) Return Issue objects filtered by the title column
 * @method array findByIssueNumber(string $issue_number) Return Issue objects filtered by the issue_number column
 * @method array findBySerieId(int $serie_id) Return Issue objects filtered by the serie_id column
 * @method array findByPubDate(string $pub_date) Return Issue objects filtered by the pub_date column
 * @method array findByCvId(string $cv_id) Return Issue objects filtered by the cv_id column
 * @method array findByCvUrl(string $cv_url) Return Issue objects filtered by the cv_url column
 *
 * @package    propel.generator.comicslist.om
 */
abstract class BaseIssueQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseIssueQuery object.
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
            $modelName = 'Issue';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new IssueQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   IssueQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return IssueQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof IssueQuery) {
            return $criteria;
        }
        $query = new IssueQuery(null, null, $modelAlias);

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
     * @return   Issue|Issue[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IssuePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(IssuePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Issue A model object, or null if the key is not found
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
     * @return                 Issue A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `issue_number`, `serie_id`, `pub_date`, `cv_id`, `cv_url` FROM `comics_issue` WHERE `id` = :p0';
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
            $obj = new Issue();
            $obj->hydrate($row);
            IssuePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Issue|Issue[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Issue[]|mixed the list of results, formatted by the current formatter
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
     * @return IssueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IssuePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return IssueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IssuePeer::ID, $keys, Criteria::IN);
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
     * @return IssueQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IssuePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IssuePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IssuePeer::ID, $id, $comparison);
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
     * @return IssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssuePeer::TITLE, $title, $comparison);
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
     * @return IssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssuePeer::ISSUE_NUMBER, $issueNumber, $comparison);
    }

    /**
     * Filter the query on the serie_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySerieId(1234); // WHERE serie_id = 1234
     * $query->filterBySerieId(array(12, 34)); // WHERE serie_id IN (12, 34)
     * $query->filterBySerieId(array('min' => 12)); // WHERE serie_id >= 12
     * $query->filterBySerieId(array('max' => 12)); // WHERE serie_id <= 12
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
     * @return IssueQuery The current query, for fluid interface
     */
    public function filterBySerieId($serieId = null, $comparison = null)
    {
        if (is_array($serieId)) {
            $useMinMax = false;
            if (isset($serieId['min'])) {
                $this->addUsingAlias(IssuePeer::SERIE_ID, $serieId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($serieId['max'])) {
                $this->addUsingAlias(IssuePeer::SERIE_ID, $serieId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IssuePeer::SERIE_ID, $serieId, $comparison);
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
     * @return IssueQuery The current query, for fluid interface
     */
    public function filterByPubDate($pubDate = null, $comparison = null)
    {
        if (is_array($pubDate)) {
            $useMinMax = false;
            if (isset($pubDate['min'])) {
                $this->addUsingAlias(IssuePeer::PUB_DATE, $pubDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pubDate['max'])) {
                $this->addUsingAlias(IssuePeer::PUB_DATE, $pubDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IssuePeer::PUB_DATE, $pubDate, $comparison);
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
     * @return IssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssuePeer::CV_ID, $cvId, $comparison);
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
     * @return IssueQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IssuePeer::CV_URL, $cvUrl, $comparison);
    }

    /**
     * Filter the query by a related Serie object
     *
     * @param   Serie|PropelObjectCollection $serie The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IssueQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySerie($serie, $comparison = null)
    {
        if ($serie instanceof Serie) {
            return $this
                ->addUsingAlias(IssuePeer::SERIE_ID, $serie->getId(), $comparison);
        } elseif ($serie instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IssuePeer::SERIE_ID, $serie->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySerie() only accepts arguments of type Serie or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Serie relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return IssueQuery The current query, for fluid interface
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
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   SerieQuery A secondary query class using the current class as primary query
     */
    public function useSerieQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSerie($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Serie', 'SerieQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Issue $issue Object to remove from the list of results
     *
     * @return IssueQuery The current query, for fluid interface
     */
    public function prune($issue = null)
    {
        if ($issue) {
            $this->addUsingAlias(IssuePeer::ID, $issue->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
