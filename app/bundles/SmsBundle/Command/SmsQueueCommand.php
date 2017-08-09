<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-08
 * Time: 11:01
 */

namespace Mautic\SmsBundle\Command;


use Mautic\CoreBundle\Command\ModeratedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SmsQueueCommand extends ModeratedCommand
{
    protected function configure()
    {
        $this
            ->setName('mautic:smses:send')
            ->setDescription('Processes Sms\'s queue')
            ->addOption('--message-id','-m',InputOption::VALUE_OPTIONAL,'The message id.')
            ->addOption('--segment-id','-se',InputOption::VALUE_OPTIONAL,'The segment id.');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processed  =   0;
        $container = $this->getContainer();
        $translator = $container->get('translator');
        $segmentId = $input->getoption('segment-id');
        $messageId = $input->getOption('message-id');

        $output->write('hello world!');

        return 0;
    }
}