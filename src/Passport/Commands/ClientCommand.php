<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 17:58
 */
namespace Notadd\Passport\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Notadd\Foundation\Passport\PersonalAccessClient;
use Notadd\Foundation\Passport\Repositories\ClientRepository;
use Symfony\Component\Console\Input\InputOption;
/**
 * Class ClientCommand
 * @package Notadd\Passport\Commands
 */
class ClientCommand extends AbstractCommand {
    /**
     * @var \Notadd\Foundation\Passport\Repositories\ClientRepository
     */
    protected $clients;
    /**
     * ClientCommand constructor.
     * @param \Notadd\Foundation\Passport\Repositories\ClientRepository $clients
     */
    public function __construct(ClientRepository $clients) {
        parent::__construct();
        $this->clients = $clients;
    }
    /**
     * @return void
     */
    public function configure() {
        $this->setDescription('Create a client for issuing access tokens');
        $this->setName('passport:client');
        $this->addOption('personal', null, InputOption::VALUE_OPTIONAL, 'Create a personal access token client.');
        $this->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Create a password grant client.');
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The name of the client.');
    }
    /**
     * @return void
     */
    protected function createAuthCodeClient() {
        $userId = $this->output->ask('Which user ID should the client be assigned to?');
        $name = $this->input->getOption('name') ?: $this->output->ask('What should we name the client?');
        $redirect = $this->output->ask('Where should we redirect the request after authorization?', url('/auth/callback'));
        $client = $this->clients->create($userId, $name, $redirect);
        $this->info('New client created successfully.');
        $this->info('<comment>Client ID:</comment> ' . $client->id);
        $this->info('<comment>Client secret:</comment> ' . $client->secret);
    }
    /**
     * @return void
     */
    protected function createPasswordClient() {
        $name = $this->input->getOption('name') ?: $this->output->ask('What should we name the password grant client?', config('app.name') . ' Password Grant Client');
        $this->clients->createPasswordGrantClient(null, $name, 'http://localhost');
        $this->info('Password grant client created successfully.');
    }
    /**
     * @return void
     */
    protected function createPersonalClient() {
        $name = $this->input->getOption('name') ?: $this->output->ask('What should we name the personal access client?', config('app.name') . ' Personal Access Client');
        $client = $this->clients->createPersonalAccessClient(null, $name, 'http://localhost');
        $accessClient = new PersonalAccessClient();
        $accessClient->setAttribute('client_id', $client->getAttribute('id'));
        $accessClient->save();
        $this->info('Personal access client created successfully.');
    }
    /**
     * @return void
     */
    public function fire() {
        if($this->input->getOption('personal')) {
            $this->createPersonalClient();
            return;
        }
        if($this->input->getOption('password')) {
            $this->createPasswordClient();
            return;
        }
        $this->createAuthCodeClient();
    }
}