# Doctrine repository service provider for Silex

This service provider exposes an easy way to have Repositories for your silex database.

## Requirements

This service provider has ben built to work with the `DoctrineServiceProvider` silex extension. See [Silex' DoctrineServiceProvider documentation](http://silex.sensiolabs.org/doc/providers/doctrine.html) for instruction on how to use it.

## Configuration

Register the service provider:

    $app->register(
        new Knp\Provider\RepositoryServiceProvider(), array(
            'repository.repositories' => array(
                'database1' => array(
                    'projects' => 'MyProject\Repository\Project',
                ),
                'database2' => array(
                    'users' => 'MyProject\Repository\User',
                ),
            )
        )
    );

The service provider expects parameter `repository.repositories` to be set and to be an array where keys are connection names (of multiple databases, managed by `dbs.options`) 
and each connection name is an associative array with service names as keys and repository classes as values.

In the example above, the `database1_projects` service will be exposed by Pimple (ie, you can access it through `$app['database1_projects']`) using the `MyProject\Repository\Project` class, and it refers to `database1` connection.
In the same way, the `database2_users` service will be exposed using the `MyProject\Repository\User` class, and it refers to to `database2` connection.

## Usage

As you might have guessed by now, you need to implement a concrete class for every repository that you want to use. That repository must extend `Knp\Repository` and implement the `getTableName` method, that should return the database's table name bound to that repository.

In the example above, given your projects are stored in the `project` table, the `MyProject\Repository\Project` class would look like that:

    <?php

    namespace MyProject\Repository;

    use Knp\Repository;

    class Project extends Repository;
    {
        public function getTableName()
        {
            return 'project';
        }
    }

The default repository implementation exposes a number of methods to manipulate your database that are basically proxies to methods from `Doctrine\DBAL\Connection`:

### `insert(array $data)`

    $app['database1_projects']->insert(array(
        'title'       => 'foo',
        'description' => 'A project'
    ));

Will insert a project in the table with `title` "foo" and `description` "A project".

### `update(array $data, array $identifier)`

    $app['database1_projects']->update(array('title' => 'bar'), array('title' => 'foo'));

Will update all projects' `title` from "foo" to "bar".

### `delete(array $identifier)`

    $app['database1_projects']->delete(array('title' => 'bar'));

Will update all projects which title is "bar".

### `find($id)`

    $app['database1_projects']->find(42);

Returns the project which primary key is 42.

### `findAll()`

    $app['database1_projects']->findAll();

Returns the entire table content.

### Extending a repository

Extending a repository is as easy as adding methods to it. For example, you could add a `findByTitle($title)` method to return all projects based on their title:

    public function findByTitle($title)
    {
        return $this->db->fetchAll('SELECT * FROM project WHERE title = ?', array($title));
    }

## Credits

* [KnpLabs](http://knplabs.com/) for original code
* [Joshua Morse](https://github.com/joshuamorse/) for initial code extraction
