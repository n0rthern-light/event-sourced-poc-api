<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use App\Shared\Infrastructure\Doctrine\AbstractRepository;
use App\Shared\Infrastructure\SQL\SelectSqlBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use Nlf\Component\Event\Aggregate\Event\EventStoreQueryCriteria;

class EventRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function lastIndexOfAggregate(string $aggregateUuid): ?int
    {
        return $this->createQueryBuilder('e')
            ->select('e.id')
            ->where('e.aggregateUuid = :aggregateUuid')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('aggregateUuid', $aggregateUuid)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    public function findAllByCriteria(EventStoreQueryCriteria $criteria): array
    {
        $sqlBuilder = (new SelectSqlBuilder())
            ->what(['*'])
            ->from('event', 'e');

        $params = [];

        if ($criteria->hasAggregateUuid()) {
            $sqlBuilder->where('e.aggregate_uuid = :aggregateUuid');
            $params['aggregateUuid'] = (string)$criteria->getAggregateUuid();
        }

        if ($criteria->hasEventUuid()) {
            $sqlBuilder->where('e.event_uuid = :eventUuid');
            $params['eventUuid'] = (string)$criteria->getEventUuid();
        }

        if ($criteria->hasEventName()) {
            $sqlBuilder->where('e.event_name = :eventName');
            $params['eventName'] = $criteria->getEventName();
        }

        if ($criteria->hasPayloadCriteria()) {
            $it = 0;
            foreach($criteria->getPayloadCriteria() as $key => $value) {
                $paramKey = 'jsonField' . $it;
                $paramValue = 'jsonValue' . $it;

                $sqlBuilder->where("e.payload->>:{$paramKey} = :{$paramValue}");

                $params[$paramKey] = $key;
                $params[$paramValue] = $value;

                $it++;
            }
        }

        $sqlBuilder->orderBy('e.id', 'ASC');

        $sql = $sqlBuilder->buildSQL();
        $pdo = $this->getEntityManager()->getConnection();
        $rows = $pdo->fetchAllAssociative($sql, $params);

        return \array_map(function(array $row) {
            return new Event(
                (int)$row['id'],
                new StringUuid($row['event_uuid']),
                new StringUuid($row['aggregate_uuid']),
                $row['event_name'],
                \json_decode($row['payload'], true),
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['created_at'])
            );
        }, $rows);
    }
}
