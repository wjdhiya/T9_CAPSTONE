# TODO: Rename 'tahun_akademik' to 'tahun'

## Migration
- [x] Create migration to rename column in penelitian, publikasi, pengabdian_masyarakat tables

## Models
- [x] Update Penelitian model fillable and scopes
- [x] Update Publikasi model fillable and scopes
- [x] Update PengabdianMasyarakat model fillable and scopes

## Controllers
- [x] Update ReportController to use 'tahun'
- [x] Update PenelitianController to use 'tahun'
- [x] Update PublikasiController to use 'tahun'
- [x] Update PengabdianMasyarakatController to use 'tahun'

## Views
- [ ] Update penelitian/create.blade.php - change form field from 'tahun_akademik' to 'tahun'
- [ ] Update penelitian/edit.blade.php - change form field from 'tahun_akademik' to 'tahun'
- [ ] Update penelitian/index.blade.php - change filter and display from 'tahun_akademik' to 'tahun'
- [ ] Update penelitian/show.blade.php - change display from 'tahun_akademik' to 'tahun'
- [ ] Update publikasi/create.blade.php - change form field from 'tahun_akademik' to 'tahun'
- [ ] Update publikasi/edit.blade.php - change form field from 'tahun_akademik' to 'tahun'
- [ ] Update publikasi/index.blade.php - change filter and display from 'tahun_akademik' to 'tahun'
- [ ] Update publikasi/show.blade.php - change display from 'tahun_akademik' to 'tahun'
- [ ] Update pengmas/create.blade.php - change form field from 'tahun_akademik' to 'tahun'
- [ ] Update pengmas/edit.blade.php - change form field from 'tahun_akademik' to 'tahun'
- [ ] Update pengmas/index.blade.php - change filter and display from 'tahun_akademik' to 'tahun'
- [ ] Update pengmas/show.blade.php - change display from 'tahun_akademik' to 'tahun'
- [ ] Update reports/index.blade.php - change filter from 'tahun_akademik' to 'tahun'

## Seeders
- [ ] Check and update seeders if needed

## Final Steps
- [ ] Run migration
- [ ] Re-seed database
- [ ] Test the application
