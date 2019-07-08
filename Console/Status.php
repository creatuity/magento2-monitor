<?php

namespace Creatuity\Monitor\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Status extends Command
{

    /** @var \Creatuity\Monitor\Api\Data\Status */
    protected $status;


	public function __construct(
		\Creatuity\Monitor\Api\Data\Status $status,
		string $name = NULL
	) {
		parent::__construct($name);
		$this->status = $status;
	}

    protected function configure()
    {
        $this->setName('creatuity:monitor:status')
            ->setDescription('Check system health');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->status->getStatus() as $key => $value) {
            $tag = $value ? 'info' : 'error';
            $output->writeln('<' . $tag . '>' . $key . ': ' . ($value ? 'OK' : 'ERROR') . '</' . $tag . '>');
        }
    }

}