<?php

namespace Database\\Seeders;

use App\\Models\\PartListing;
use App\\Models\\User;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\Hash;
use Illuminate\\Support\\Str;

class PartMarketplaceDemoSeeder extends Seeder
{
    /**
     * Creates clearly-labelled sample listings for the public marketplace.
     * Safe to run repeatedly: records are upserted by stable email/slug.
     */
    public function run(): void
    {
        $sellers = [
            ['name' => 'გიორგი მ.', 'email' => 'demo.seller01@motohub.test'],
            ['name' => 'ნიკა კ.', 'email' => 'demo.seller02@motohub.test'],
            ['name' => 'ლევან დ.', 'email' => 'demo.seller03@motohub.test'],
            ['name' => 'დავით გ.', 'email' => 'demo.seller04@motohub.test'],
            ['name' => 'საბა ჯ.', 'email' => 'demo.seller05@motohub.test'],
            ['name' => 'ირაკლი ბ.', 'email' => 'demo.seller06@motohub.test'],
            ['name' => 'მარიამი რ.', 'email' => 'demo.seller07@motohub.test'],
            ['name' => 'ალექსი ნ.', 'email' => 'demo.seller08@motohub.test'],
        ];

        $users = collect($sellers)->mapWithKeys(function (array $seller) {
            $user = User::firstOrCreate(
                ['email' => $seller['email']],
                ['name' => $seller['name'], 'password' => Hash::make(Str::random(48))]
            );

            if ($user->name !== $seller['name']) {
                $user->update(['name' => $seller['name']]);
            }

            return [$seller['email'] => $user];
        });

        $listings = [
            ['slug'=>'demo-yamaha-r1-oem-clutch-lever','seller'=>0,'title'=>'Yamaha R1 OEM сцეპლენიის ბერკეტი','brand'=>'Yamaha','category'=>'კონტროლი / ბერკეტები','condition'=>'used','part_type'=>'oem','part_number'=>'2CR-83912','motorcycle_brand'=>'Yamaha','motorcycle_model'=>'YZF-R1','motorcycle_year'=>2015,'engine_cc'=>1000,'price'=>120,'description'=>'ორიგინალი ბერკეტი, მოხსნილია გამართული მოტოციკლიდან. აქვს გამოყენების მსუბუქი კვალი, მოღუნული ან შეკეთებული არ არის. თავსებადობა გადაამოწმეთ ნაწილის კოდით.'],
            ['slug'=>'demo-honda-cbr600rr-front-brake-disc','seller'=>1,'title'=>'Honda CBR600RR წინა სამუხრუჭე დისკი','brand'=>'Honda','category'=>'მუხრუჭები','condition'=>'used','part_type'=>'oem','part_number'=>'45251-MFJ-D00','motorcycle_brand'=>'Honda','motorcycle_model'=>'CBR600RR','motorcycle_year'=>2007,'engine_cc'=>600,'price'=>260,'description'=>'ორიგინალი წინა დისკი კარგ მდგომარეობაში. ზედაპირი თანაბარია, კრიტიკული ცვეთა არ აღენიშნება. შეძენამდე რეკომენდებულია სისქის გაზომვა და თქვენი მოდელის შესაბამისობის გადამოწმება.'],
            ['slug'=>'demo-kawasaki-zx10r-mirror-set','seller'=>2,'title'=>'Kawasaki ZX-10R სარკეების წყვილი','brand'=>'Kawasaki','category'=>'კორპუსი და პლასტმასები','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Kawasaki','motorcycle_model'=>'Ninja ZX-10R','motorcycle_year'=>2016,'engine_cc'=>1000,'price'=>190,'description'=>'ორიგინალი მარცხენა და მარჯვენა სარკე. სამაგრები დაუზიანებელია, მინებზე მცირე კოსმეტიკური ნაკაწრებია. იყიდება წყვილად.'],
            ['slug'=>'demo-bmw-s1000rr-air-filter-new','seller'=>3,'title'=>'BMW S1000RR ჰაერის ფილტრი — ახალი','brand'=>'BMW','category'=>'ძრავი და ჰაერის სისტემა','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'BMW','motorcycle_model'=>'S1000RR','motorcycle_year'=>2019,'engine_cc'=>1000,'price'=>95,'description'=>'ახალი, შეფუთვიდან ამოუღებელი ჰაერის ფილტრი. განკუთვნილია მითითებული თაობისთვის; შეკვეთამდე შეადარეთ ძველი ფილტრის ფორმა და კატალოგის ნომერი.'],
            ['slug'=>'demo-suzuki-gsxr750-exhaust-midpipe','seller'=>4,'title'=>'Suzuki GSX-R750 გამონაბოლქვის შუა მილი','brand'=>'Suzuki','category'=>'გამონაბოლქვი','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Suzuki','motorcycle_model'=>'GSX-R750','motorcycle_year'=>2011,'engine_cc'=>750,'price'=>220,'description'=>'ქარხნული შუა მილი, შედუღების გარეშე. აქვს ჩვეულებრივი თერმული შეფერილობა და ზედაპირული ნაკაწრები. ფოტოები და ზომები შეთავაზების მაგალითისთვისაა; რეალურ შეძენამდე შეამოწმეთ შეერთებები.'],
            ['slug'=>'demo-ktm-duke390-led-indicator','seller'=>5,'title'=>'KTM Duke 390 LED მოხვევის მაჩვენებლები','brand'=>'KTM','category'=>'ელექტროობა და განათება','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'KTM','motorcycle_model'=>'Duke 390','motorcycle_year'=>2020,'engine_cc'=>390,'price'=>75,'description'=>'ახალი LED მაჩვენებლების კომპლექტი. უნივერსალური სამაგრი, გაყვანილობის შეერთება შესაძლოა მოდელზე მორგებას საჭიროებდეს.'],
            ['slug'=>'demo-yamaha-mt07-seat','seller'=>6,'title'=>'Yamaha MT-07 ორიგინალი სავარძელი','brand'=>'Yamaha','category'=>'სავარძლები და კომფორტი','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Yamaha','motorcycle_model'=>'MT-07','motorcycle_year'=>2018,'engine_cc'=>700,'price'=>180,'description'=>'ორიგინალი სავარძელი, ნაკერი მთელია და ღრმა დაზიანება არ აქვს. ზედაპირზე გამოყენების მსუბუქი კვალია.'],
            ['slug'=>'demo-honda-cbr1000rr-fairing-kit','seller'=>7,'title'=>'Honda CBR1000RR გვერდითი პლასტმასის კომპლექტი','brand'=>'Honda','category'=>'კორპუსი და პლასტმასები','condition'=>'used','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Honda','motorcycle_model'=>'CBR1000RR','motorcycle_year'=>2012,'engine_cc'=>1000,'price'=>340,'description'=>'მარცხენა და მარჯვენა გვერდითი პლასტმასები. მცირე კოსმეტიკური ნაკაწრები აქვს; სამაგრების მდგომარეობა ადგილზე უნდა შემოწმდეს.'],
            ['slug'=>'demo-yamaha-r6-radiator','seller'=>0,'title'=>'Yamaha YZF-R6 რადიატორი','brand'=>'Yamaha','category'=>'გაგრილების სისტემა','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Yamaha','motorcycle_model'=>'YZF-R6','motorcycle_year'=>2008,'engine_cc'=>600,'price'=>280,'description'=>'ორიგინალი რადიატორი, ვიზუალურად სწორი გეომეტრიით. შეძენამდე აუცილებელია წნევაზე შემოწმება; მოყვება მხოლოდ ის, რაც განცხადებაშია აღწერილი.'],
            ['slug'=>'demo-triumph-street-triple-chain-kit','seller'=>1,'title'=>'Triumph Street Triple გადაცემის კომპლექტი','brand'=>'Triumph','category'=>'ტრანსმისია და ჯაჭვი','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Triumph','motorcycle_model'=>'Street Triple','motorcycle_year'=>2017,'engine_cc'=>765,'price'=>310,'description'=>'ახალი ჯაჭვისა და ვარსკვლავების კომპლექტი. კბილების რაოდენობა და ჯაჭვის სიგრძე გადაამოწმეთ თქვენი მოდელის ვერსიასთან.'],
            ['slug'=>'demo-kawasaki-ninja650-front-fender','seller'=>2,'title'=>'Kawasaki Ninja 650 წინა ფრთა','brand'=>'Kawasaki','category'=>'კორპუსი და პლასტმასები','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Kawasaki','motorcycle_model'=>'Ninja 650','motorcycle_year'=>2017,'engine_cc'=>650,'price'=>140,'description'=>'ორიგინალი წინა ფრთა. სამაგრები ადგილზეა, ზედაპირზე მცირე ნაკაწრები ეტყობა. ფერი და კოდი გადაამოწმეთ შეკვეთამდე.'],
            ['slug'=>'demo-bmw-f800gs-handguards','seller'=>3,'title'=>'BMW F800GS ხელის დამცავები','brand'=>'BMW','category'=>'აქსესუარები','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'BMW','motorcycle_model'=>'F800GS','motorcycle_year'=>2015,'engine_cc'=>800,'price'=>160,'description'=>'ორიგინალი მარცხენა-მარჯვენა დამცავები სამაგრებით. გამოყენების კვალი აქვს, ბზარი არ ჩანს.'],
            ['slug'=>'demo-suzuki-vstrom650-windscreen','seller'=>4,'title'=>'Suzuki V-Strom 650 საქარე მინა','brand'=>'Suzuki','category'=>'აქსესუარები','condition'=>'used','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Suzuki','motorcycle_model'=>'V-Strom 650','motorcycle_year'=>2019,'engine_cc'=>650,'price'=>130,'description'=>'ტურინგის ტიპის საქარე მინა. გამჭვირვალეა, აქვს მსუბუქი ზედაპირული ნაკაწრები. სამაგრების დაშორება გადაამოწმეთ თქვენს ვერსიაზე.'],
            ['slug'=>'demo-ktm-790-duke-clutch-cover','seller'=>5,'title'=>'KTM 790 Duke ძრავის გვერდითი ხუფი','brand'=>'KTM','category'=>'ძრავი','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'KTM','motorcycle_model'=>'790 Duke','motorcycle_year'=>2019,'engine_cc'=>790,'price'=>210,'description'=>'ორიგინალი ხუფი მოხსნილია ნაწილებად დაშლილი ძრავიდან. ბზარი არ ჩანს, შუასადები კომპლექტში არ შედის.'],
            ['slug'=>'demo-yamaha-r1-rearset-pegs','seller'=>6,'title'=>'Yamaha R1 უკანა ფეხის საყრდენები','brand'=>'Yamaha','category'=>'ფეხის საყრდენები','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Yamaha','motorcycle_model'=>'YZF-R1','motorcycle_year'=>2016,'engine_cc'=>1000,'price'=>175,'description'=>'ორიგინალი მარცხენა და მარჯვენა უკანა საყრდენები. სამაგრები და რეზინები ადგილზეა; კოსმეტიკური კვალი აქვს.'],
            ['slug'=>'demo-honda-cb650r-mirror-new','seller'=>7,'title'=>'Honda CB650R მარცხენა სარკე — ახალი','brand'=>'Honda','category'=>'კონტროლი / სარკეები','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Honda','motorcycle_model'=>'CB650R','motorcycle_year'=>2021,'engine_cc'=>650,'price'=>85,'description'=>'ახალი შემცვლელი სარკე. შეადარეთ ხრახნის მიმართულება და სამაგრის დიამეტრი ძველ დეტალს.'],
            ['slug'=>'demo-kawasaki-z900-radiator-fan','seller'=>0,'title'=>'Kawasaki Z900 რადიატორის ვენტილატორი','brand'=>'Kawasaki','category'=>'გაგრილების სისტემა','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Kawasaki','motorcycle_model'=>'Z900','motorcycle_year'=>2018,'engine_cc'=>900,'price'=>150,'description'=>'ორიგინალი ვენტილატორი ძრავით. კონექტორი დაუზიანებელია; მონტაჟამდე შეამოწმეთ ელექტრული გამართულობა.'],
            ['slug'=>'demo-bmw-r1250gs-crash-bars','seller'=>1,'title'=>'BMW R1250GS ძრავის დამცავი რკალები','brand'=>'BMW','category'=>'დამცავი აქსესუარები','condition'=>'used','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'BMW','motorcycle_model'=>'R1250GS','motorcycle_year'=>2020,'engine_cc'=>1250,'price'=>390,'description'=>'მეტალის დამცავი რკალების წყვილი. აქვს გამოყენების კვალი, დეფორმაცია არ უნდა ჰქონდეს — საბოლოო შემოწმება აუცილებელია.'],
            ['slug'=>'demo-suzuki-gsxr1000-regulator','seller'=>2,'title'=>'Suzuki GSX-R1000 ძაბვის რეგულატორი','brand'=>'Suzuki','category'=>'ელექტროობა','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Suzuki','motorcycle_model'=>'GSX-R1000','motorcycle_year'=>2014,'engine_cc'=>1000,'price'=>115,'description'=>'ორიგინალი დამტენი სისტემის რეგულატორი. გაყიდვამდე/შეძენამდე სასურველია დატენვის ძაბვის ტესტით შემოწმება.'],
            ['slug'=>'demo-ducati-monster821-exhaust','seller'=>3,'title'=>'Ducati Monster 821 ქარხნული მაყუჩი','brand'=>'Ducati','category'=>'გამონაბოლქვი','condition'=>'used','part_type'=>'oem','part_number'=>null,'motorcycle_brand'=>'Ducati','motorcycle_model'=>'Monster 821','motorcycle_year'=>2016,'engine_cc'=>821,'price'=>450,'description'=>'ქარხნული მაყუჩი, კორპუსზე თერმული შეფერილობით. შეამოწმეთ სამაგრები და ჰომოლოგაციის მოთხოვნები თქვენს მოდელზე.'],
            ['slug'=>'demo-yamaha-mt09-brake-pads','seller'=>4,'title'=>'Yamaha MT-09 წინა სამუხრუჭე ხუნდები','brand'=>'Yamaha','category'=>'მუხრუჭები','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Yamaha','motorcycle_model'=>'MT-09','motorcycle_year'=>2020,'engine_cc'=>900,'price'=>105,'description'=>'ახალი ხუნდების კომპლექტი. შეარჩიეთ კალიპერისა და დისკის ზუსტი ვერსიის მიხედვით; მუხრუჭის ნაწილების მონტაჟი სპეციალისტს მიანდეთ.'],
            ['slug'=>'demo-honda-africa-twin-topcase','seller'=>5,'title'=>'Honda Africa Twin უკანა ბარგის ყუთი','brand'=>'Honda','category'=>'ბარგის სისტემები','condition'=>'used','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Honda','motorcycle_model'=>'Africa Twin','motorcycle_year'=>2018,'engine_cc'=>1000,'price'=>290,'description'=>'მყარი ბარგის ყუთი საკეტით. კორპუსზე კოსმეტიკური ნაკაწრებია; სამაგრი ფირფიტის თავსებადობა წინასწარ გადაამოწმეთ.'],
            ['slug'=>'demo-ktm-rc390-clutch-cable','seller'=>6,'title'=>'KTM RC 390 сцეპლენიის ტროსი','brand'=>'KTM','category'=>'კონტროლი და ტროსები','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'KTM','motorcycle_model'=>'RC 390','motorcycle_year'=>2022,'engine_cc'=>390,'price'=>45,'description'=>'ახალი შემცვლელი ტროსი. სიგრძე და დაბოლოებები შეადარეთ ძველ დეტალს, რადგან ბაზრის ვერსიებს შორის განსხვავება შეიძლება იყოს.'],
            ['slug'=>'demo-aprilia-rsv4-tail-tidy','seller'=>7,'title'=>'Aprilia RSV4 სანომრე ნიშნის სამაგრი','brand'=>'Aprilia','category'=>'აქსესუარები','condition'=>'new','part_type'=>'aftermarket','part_number'=>null,'motorcycle_brand'=>'Aprilia','motorcycle_model'=>'RSV4','motorcycle_year'=>2019,'engine_cc'=>1000,'price'=>125,'description'=>'ახალი კომპაქტური სანომრე ნიშნის სამაგრი. განათებისა და მიმართულების მაჩვენებლების შეერთება შესაძლოა გადაკეთებას საჭიროებდეს.'],
        ];

        foreach ($listings as $listing) {
            $sellerIndex = $listing['seller'];
            unset($listing['seller']);

            PartListing::updateOrCreate(
                ['slug' => $listing['slug']],
                array_merge($listing, [
                    'user_id' => $users->values()->get($sellerIndex)->id,
                    'image_path' => null,
                    'status' => 'published',
                    'is_demo' => true,
                ])
            );
        }
    }
}
