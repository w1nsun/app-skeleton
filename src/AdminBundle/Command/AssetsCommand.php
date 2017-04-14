<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class AssetsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:assets:install')
            ->setDescription('Install admin theme assets.')
            ->setHelp('Install admin theme assets...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDir = $this->getContainer()->get('kernel')->getRootDir() . '/..';
        $destinationSrcDir = $rootDir . '/web/assets/admin/build';
        $destinationSrcVendorDir = $rootDir . '/web/assets/admin/vendors';
        $srcDir = $rootDir . '/node_modules/gentelella/build';
        $srcVendorDir = $rootDir . '/node_modules/gentelella/vendors';

        $fs = new Filesystem();
        $fs->symlink($srcDir, $destinationSrcDir, true);
        $fs->symlink($srcVendorDir, $destinationSrcVendorDir, true);
        $output->writeln('<fg=white;bg=green>ok</>');
    }
}
