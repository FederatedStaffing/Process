
$process = new Process();

if ($process->create()) {
    echo "Parent...waiting\n";
    $process->wait();
    echo "Parent done\n";

} else {
    echo "Child....sleeping\n";
    sleep(2);
    echo "Child Exiting\n";
    exit;
}
