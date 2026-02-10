### Tambahin di function print di printercontroller
```
if ($trx) {
    $trx->update(['status' => 'completed']);
                
    // 2. TIMBULKAN EVENT untuk reload halaman admin/owner
    $outletId = $file->station->outlet_id;
    event(new NewTransactionCreated($outletId));
}
```

ini di importnya
```
use App\Events\NewTransactionCreated;
```

### Ubah 60vh atau 65 vh di id fileList