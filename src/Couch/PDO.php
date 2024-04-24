<?php

namespace nailfor\Couchbase\Couch;

use Couchbase\Cluster;
use Couchbase\ClusterOptions;
use Couchbase\QueryOptions;

class PDO
{
    protected Cluster $client;

    protected string $query;

    protected array $bindings = [];

    public function __construct($dsn, $username, $password, $options)
    {
        $options = new ClusterOptions();
        $options->credentials(
            $username,
            $password
        );
        $options->connectTimeout(1000);
        $options->bootstrapTimeout(1000);

        $this->client = new Cluster($dsn, $options);
    }

    public function prepare(string $query): self
    {
        $this->query = $query;
        $this->bindings = [];

        return $this;
    }

    public function bindValue(string|int $param, mixed $value)
    {
        $this->bindings[$param] = $value;
    }
    
    public function execute(): void
    {
    }

    public function fetchAll(): ?array
    {
        $queryOptions = new QueryOptions();
        $queryOptions->positionalParameters($this->bindings);
        $result = $this->client->query($this->query, $queryOptions);

        return $result->rows();
    }
}
