<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Primero creamos toda la estructura de Roles y Permisos de Spatie
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // 2. Creamos al usuario Administrador
        $admin = User::create([
            'first_name' => 'Admin',
            'paternal_surname' => 'Colegio',
            'maternal_surname' => 'Andino',
            'dni' => '75707786',
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'status' => 'Activo',
        ]);

        // Le asignamos el rol
        $admin->assignRole('Administrador');

        // 3. Lista de usuarios con rol "Usuario"
        $usuarios = [
            // Tus usuarios iniciales
            [
                'first_name' => 'Yorshyo Artemio',
                'paternal_surname' => 'Villena',
                'maternal_surname' => 'Ochoa',
                'dni' => '75707785',
                'email' => 'yvillena@andino.edu.pe',
            ],
            // Nuevos usuarios solicitados
            [
                'first_name' => 'Abdel',
                'paternal_surname' => 'Escobar',
                'maternal_surname' => 'Galvan',
                'dni' => '19957801',
                'email' => 'aescobar@andino.edu.pe',
            ],
            [
                'first_name' => 'Agustin Angel',
                'paternal_surname' => 'Gutierrez',
                'maternal_surname' => 'Falcon',
                'dni' => '20039684',
                'email' => 'agutierrez@andino.edu.pe',
            ],
            [
                'first_name' => 'Alex Ricardo',
                'paternal_surname' => 'Aponte',
                'maternal_surname' => 'Espinoza',
                'dni' => '42907725',
                'email' => 'aaponte@andino.edu.pe',
            ],
            [
                'first_name' => 'Alfredo Felix',
                'paternal_surname' => 'Navarro',
                'maternal_surname' => 'Ladera',
                'dni' => '19986632',
                'email' => 'anavarro@andino.edu.pe',
            ],
            [
                'first_name' => 'Ana Esther',
                'paternal_surname' => 'Figueroa',
                'maternal_surname' => 'Sanchez',
                'dni' => '19958163',
                'email' => 'afigueroa@andino.edu.pe',
            ],
            [
                'first_name' => 'Blas',
                'paternal_surname' => 'Mitma',
                'maternal_surname' => 'De La Cruz',
                'dni' => '19807943',
                'email' => 'bmitma@andino.edu.pe',
            ],
            [
                'first_name' => 'Bricely Kuhr',
                'paternal_surname' => 'Rivera',
                'maternal_surname' => 'Porras',
                'dni' => '42468257',
                'email' => 'brivera@andino.edu.pe',
            ],
            [
                'first_name' => 'Carmen Maribel',
                'paternal_surname' => 'Rabanal',
                'maternal_surname' => 'Enciso',
                'dni' => '20084613',
                'email' => 'crabanal@andino.edu.pe',
            ],
            [
                'first_name' => 'Catherine Magaly',
                'paternal_surname' => 'Gonzales',
                'maternal_surname' => 'Miguel',
                'dni' => '41684241',
                'email' => 'cgonzales@andino.edu.pe',
            ],
            [
                'first_name' => 'Daniel Jhoan',
                'paternal_surname' => 'Rajo',
                'maternal_surname' => 'Romani',
                'dni' => '45499229',
                'email' => 'drajo@andino.edu.pe',
            ],
            [
                'first_name' => 'Danny Guillermo',
                'paternal_surname' => 'Orrego',
                'maternal_surname' => 'Andamayo',
                'dni' => '72278319',
                'email' => 'daniguillermoorregoandamayo@gmail.com',
            ],
            [
                'first_name' => 'Diego Alejandro',
                'paternal_surname' => 'Soto',
                'maternal_surname' => 'Bullon',
                'dni' => '10647651',
                'email' => 'dsoto@andino.edu.pe',
            ],
            [
                'first_name' => 'Edgar Saul',
                'paternal_surname' => 'Casas',
                'maternal_surname' => 'Choque',
                'dni' => '48804749',
                'email' => 'ecasas@andino.edu.pe',
            ],
            [
                'first_name' => 'Edith Gladys',
                'paternal_surname' => 'Rivera',
                'maternal_surname' => 'Ponce',
                'dni' => '20074353',
                'email' => 'erivera@andino.edu.pe',
            ],
            [
                'first_name' => 'Edith Lilian',
                'paternal_surname' => 'Quevedo',
                'maternal_surname' => 'Mera',
                'dni' => '19844179',
                'email' => 'equevedo@andino.edu.pe',
            ],
            [
                'first_name' => 'Eduardo Wilder',
                'paternal_surname' => 'Pimentel',
                'maternal_surname' => 'Grande',
                'dni' => '21288141',
                'email' => 'epimentel@andino.edu.pe',
            ],
            [
                'first_name' => 'Edwin Cesar',
                'paternal_surname' => 'Mendoza',
                'maternal_surname' => 'Chaupis',
                'dni' => '40231803',
                'email' => 'emendoza@andino.edu.pe',
            ],
            [
                'first_name' => 'Elvis Luis',
                'paternal_surname' => 'Ricaldi',
                'maternal_surname' => 'Limache',
                'dni' => '43459711',
                'email' => 'ericaldi@andino.edu.pe',
            ],
            [
                'first_name' => 'Elvis Robert',
                'paternal_surname' => 'Bastidas',
                'maternal_surname' => 'Huaman',
                'dni' => '20052990',
                'email' => 'erbh@andino.edu.pe',
            ],
            [
                'first_name' => 'Enrique',
                'paternal_surname' => 'Leon',
                'maternal_surname' => 'Mui',
                'dni' => '20050172',
                'email' => 'eleon@andino.edu.pe',
            ],
            [
                'first_name' => 'Ernesto Fidel',
                'paternal_surname' => 'Velasquez',
                'maternal_surname' => 'Gutarra',
                'dni' => '20070317',
                'email' => 'evelasquez@andino.edu.pe',
            ],
            [
                'first_name' => 'Esther Elizabeth',
                'paternal_surname' => 'Carhuanca',
                'maternal_surname' => 'Fernandez',
                'dni' => '41525360',
                'email' => 'ecarhuanca@andino.edu.pe',
            ],
            [
                'first_name' => 'Esther Magdalena',
                'paternal_surname' => 'Llanco',
                'maternal_surname' => 'Zavaleta',
                'dni' => '41571187',
                'email' => 'ellanco@andino.edu.pe',
            ],
            [
                'first_name' => 'Fermin',
                'paternal_surname' => 'Bejarano',
                'maternal_surname' => 'Villanueva',
                'dni' => '19914365',
                'email' => 'fbejarano@andino.edu.pe',
            ],
            [
                'first_name' => 'Flor De Guadalupe',
                'paternal_surname' => 'Aliaga',
                'maternal_surname' => 'Poma',
                'dni' => '45694825',
                'email' => 'faliaga@andino.edu.pe',
            ],
            [
                'first_name' => 'Gino Edmond',
                'paternal_surname' => 'Muñoz',
                'maternal_surname' => 'Cordova',
                'dni' => '80633046',
                'email' => 'gmunoz@andino.edu.pe',
            ],
            [
                'first_name' => 'Ivan Camilo',
                'paternal_surname' => 'Jeremias',
                'maternal_surname' => 'Olivar',
                'dni' => '43801412',
                'email' => 'ijeremias@andino.edu.pe',
            ],
            [
                'first_name' => 'Jackelin Araceli',
                'paternal_surname' => 'Berrocal',
                'maternal_surname' => 'Retamozo',
                'dni' => '74169990',
                'email' => 'jberrocal@andino.edu.pe',
            ],
            [
                'first_name' => 'Jaime Brandon',
                'paternal_surname' => 'Aramburu',
                'maternal_surname' => 'Vilcapoma',
                'dni' => '48052396',
                'email' => 'jaramburu@andino.edu.pe',
            ],
            [
                'first_name' => 'Jenny Jessica',
                'paternal_surname' => 'Tacza',
                'maternal_surname' => 'Llacua',
                'dni' => '43258989',
                'email' => 'jtacza@andino.edu.pe',
            ],
            [
                'first_name' => 'Jessica Jannett',
                'paternal_surname' => 'Montero',
                'maternal_surname' => 'Medina',
                'dni' => '20113393',
                'email' => 'jmontero@andino.edu.pe',
            ],
            [
                'first_name' => 'Jessica Lucila',
                'paternal_surname' => 'Chavez',
                'maternal_surname' => 'Poma',
                'dni' => '43423123',
                'email' => 'jchavez@andino.edu.pe',
            ],
            [
                'first_name' => 'Jhazeel Madai',
                'paternal_surname' => 'Echevarria',
                'maternal_surname' => 'Camarena',
                'dni' => '76235396',
                'email' => 'jechevarria@andino.edu.pe',
            ],
            [
                'first_name' => 'Jhordan Felix',
                'paternal_surname' => 'Sanchez',
                'maternal_surname' => 'De La Cruz', // Mantuve el apellido compuesto junto
                'dni' => '72753863',
                'email' => 'jsanchez@andino.edu.pe',
            ],
            [
                'first_name' => 'Johan Jaime',
                'paternal_surname' => 'Matos',
                'maternal_surname' => 'Ramos',
                'dni' => '72198405',
                'email' => 'jmatosr@andino.edu.pe',
            ],
            [
                'first_name' => 'Jorge Enrique',
                'paternal_surname' => 'Cabanillas',
                'maternal_surname' => 'Meneses',
                'dni' => '71560103',
                'email' => 'jcabanillas@andino.edu.pe',
            ],
            [
                'first_name' => 'Jorge Samuel',
                'paternal_surname' => 'Fernandez',
                'maternal_surname' => 'Matos',
                'dni' => '20115914',
                'email' => 'jfernandez@andino.edu.pe',
            ],
            [
                'first_name' => 'Josefina',
                'paternal_surname' => 'Quispe',
                'maternal_surname' => 'Huarancca',
                'dni' => '71214048',
                'email' => 'jquispe@andino.edu.pe',
            ],

            //SEGUNDO BLOQUE

            [
                'first_name' => 'Joser',
                'paternal_surname' => 'Flores',
                'maternal_surname' => 'Piñares',
                'dni' => '48192130',
                'email' => 'floresjoser4@gmail.com',
            ],
            [
                'first_name' => 'Josianne Carol',
                'paternal_surname' => 'Inga',
                'maternal_surname' => 'Salazar',
                'dni' => '40548839',
                'email' => 'jinga@andino.edu.pe',
            ],
            [
                'first_name' => 'Josue Daniel',
                'paternal_surname' => 'Huaman',
                'maternal_surname' => 'Arteaga',
                'dni' => '22521337',
                'email' => 'jhuaman@andino.edu.pe',
            ],
            [
                'first_name' => 'Judith Estefany',
                'paternal_surname' => 'Matamoros',
                'maternal_surname' => 'Sanchez',
                'dni' => '77484361',
                'email' => 'jmatamoros@andino.edu.pe',
            ],
            [
                'first_name' => 'Katty Nataly',
                'paternal_surname' => 'Ramos',
                'maternal_surname' => 'Pocomucha',
                'dni' => '72877130',
                'email' => 'kramos@andino.edu.pe',
            ],

            [
                'first_name' => 'Letizia Judith',
                'paternal_surname' => 'Poma',
                'maternal_surname' => 'Garcia',
                'dni' => '73378428',
                'email' => 'lpoma@andino.edu.pe',
            ],
            [
                'first_name' => 'Liliana Guadalupe',
                'paternal_surname' => 'Herrera',
                'maternal_surname' => 'Sanabria',
                'dni' => '20074896',
                'email' => 'lherrera@andino.edu.pe',
            ],
            [
                'first_name' => 'Lucia Patricia',
                'paternal_surname' => 'Ramirez',
                'maternal_surname' => 'Romero',
                'dni' => '20118059',
                'email' => 'lramirez@andino.edu.pe',
            ],
            [
                'first_name' => 'Luisa Antolina',
                'paternal_surname' => 'Paucar',
                'maternal_surname' => 'Casas',
                'dni' => '20113159',
                'email' => 'lpaucar@andino.edu.pe',
            ],
            [
                'first_name' => 'Luis Alberto',
                'paternal_surname' => 'Moreno',
                'maternal_surname' => 'Galdos',
                'dni' => '40649772',
                'email' => 'lmoreno@andino.edu.pe',
            ],
            [
                'first_name' => 'Luis Enrique',
                'paternal_surname' => 'Goytizolo',
                'maternal_surname' => 'Rios',
                'dni' => '19827339',
                'email' => 'lgoytizolo@andino.edu.pe',
            ],
            [
                'first_name' => 'Macario',
                'paternal_surname' => 'Quispe',
                'maternal_surname' => 'Lopez',
                'dni' => '20114440',
                'email' => 'maquispe@andino.edu.pe',
            ],
            [
                'first_name' => 'Maleny Rocio',
                'paternal_surname' => 'Ataucusi',
                'maternal_surname' => 'Meza',
                'dni' => '47657153',
                'email' => 'mataucusi@andino.edu.pe',
            ],
            [
                'first_name' => 'Manuel Natividad',
                'paternal_surname' => 'Quispe',
                'maternal_surname' => 'Mamani',
                'dni' => '20053002',
                'email' => 'mquispe@andino.edu.pe',
            ],
            [
                'first_name' => 'Maria Elena',
                'paternal_surname' => 'Azabache',
                'maternal_surname' => 'Moya',
                'dni' => '20085798',
                'email' => 'mazabache@andino.edu.pe',
            ],
            [
                'first_name' => 'Maria Isabel',
                'paternal_surname' => 'Mendoza',
                'maternal_surname' => 'Solis',
                'dni' => '19870994',
                'email' => 'mmendoza@andino.edu.pe',
            ],
            [
                'first_name' => 'Maria Teresa',
                'paternal_surname' => 'Zuñiga',
                'maternal_surname' => 'Norero',
                'dni' => '19842554',
                'email' => 'mzuniga@andino.edu.pe',
            ],
            [
                'first_name' => 'Marleny Luisa',
                'paternal_surname' => 'Luna',
                'maternal_surname' => 'Aguilar',
                'dni' => '25801611',
                'email' => 'mluna@andino.edu.pe',
            ],
            [
                'first_name' => 'Max Jefferson',
                'paternal_surname' => 'Paucar',
                'maternal_surname' => 'Meza',
                'dni' => '70307672',
                'email' => 'mpaucar@andino.edu.pe',
            ],
            [
                'first_name' => 'Mayumi Florinda',
                'paternal_surname' => 'Figueroa',
                'maternal_surname' => 'Hinojosa',
                'dni' => '70325912',
                'email' => 'mfigueroah@andino.edu.pe',
            ],
            [
                'first_name' => 'Miguel',
                'paternal_surname' => 'Cervantes',
                'maternal_surname' => 'Marcos',
                'dni' => '19932084',
                'email' => 'mcervantes@andino.edu.pe',
            ],
            [
                'first_name' => 'Miriam Noemi',
                'paternal_surname' => 'Figueroa',
                'maternal_surname' => 'Sanchez',
                'dni' => '20024259',
                'email' => 'mfigueroa@andino.edu.pe',
            ],
            [
                'first_name' => 'Monica Luz',
                'paternal_surname' => 'Mayta',
                'maternal_surname' => 'Salazar',
                'dni' => '20026764',
                'email' => 'mmayta@andino.edu.pe',
            ],
            [
                'first_name' => 'Orlando Elmer',
                'paternal_surname' => 'Bermudez',
                'maternal_surname' => 'Landa',
                'dni' => '21261328',
                'email' => 'obermudez@andino.edu.pe',
            ],
            [
                'first_name' => 'Elia',
                'paternal_surname' => 'Ortega',
                'maternal_surname' => 'Veliz',
                'dni' => '20106406',
                'email' => 'eortega@andino.edu.pe',
            ],
            [
                'first_name' => 'Paola Jimena',
                'paternal_surname' => 'Guerra',
                'maternal_surname' => 'Guerra',
                'dni' => '70160831',
                'email' => 'pguerra@andino.edu.pe',
            ],
            [
                'first_name' => 'Paul David',
                'paternal_surname' => 'Arteaga',
                'maternal_surname' => 'Valdivia',
                'dni' => '40751344',
                'email' => 'parteaga@andino.edu.pe',
            ],
            [
                'first_name' => 'Persida',
                'paternal_surname' => 'Gutierrez',
                'maternal_surname' => 'Gala',
                'dni' => '46173904',
                'email' => 'pgutierrez@andino.edu.pe',
            ],
            [
                'first_name' => 'Pierina Milagros',
                'paternal_surname' => 'Barrientos',
                'maternal_surname' => 'Caso',
                'dni' => '71424251',
                'email' => 'pbarrientos@andino.edu.pe',
            ],
            [
                'first_name' => 'Piero Alesandro',
                'paternal_surname' => 'Aguilar',
                'maternal_surname' => 'Huaynalaya',
                'dni' => '71324674',
                'email' => 'paguilar@andino.edu.pe',
            ],
            [
                'first_name' => 'Piero Valentin',
                'paternal_surname' => 'Flores',
                'maternal_surname' => 'Trujillo',
                'dni' => '76697697',
                'email' => 'pflores@andino.edu.pe',
            ],
            [
                'first_name' => 'Priscila Zenina',
                'paternal_surname' => 'Paz',
                'maternal_surname' => 'Egoavil',
                'dni' => '19843000',
                'email' => 'ppaz@andino.edu.pe',
            ],
            [
                'first_name' => 'Raquel Yhovana',
                'paternal_surname' => 'Ramirez',
                'maternal_surname' => 'Montoya',
                'dni' => '41049818',
                'email' => 'rramirez@andino.edu.pe',
            ],
            [
                'first_name' => 'Raul',
                'paternal_surname' => 'Quispe',
                'maternal_surname' => 'Mamani',
                'dni' => '06762878',
                'email' => 'rquispe@andino.edu.pe',
            ],
            [
                'first_name' => 'Rebeca Raquel',
                'paternal_surname' => 'Carhuanca',
                'maternal_surname' => 'Fernandez',
                'dni' => '19824791',
                'email' => 'rcarhuanca@andino.edu.pe',
            ],


            // TERCER BLOQUE

            [
                'first_name' => 'Reyna Maricruz',
                'paternal_surname' => 'Celis',
                'maternal_surname' => 'Crespo',
                'dni' => '45223013',
                'email' => 'rcelis@andino.edu.pe',
            ],
            [
                'first_name' => 'Ricardo',
                'paternal_surname' => 'Nieves',
                'maternal_surname' => 'Suarez',
                'dni' => '20567175',
                'email' => 'rnieves@andino.edu.pe',
            ],
            [
                'first_name' => 'Roberto',
                'paternal_surname' => 'Quispe',
                'maternal_surname' => 'Escobar',
                'dni' => '74159547',
                'email' => 'robertoquispeescobar1@gmail.com',
            ],
            [
                'first_name' => 'Rocio Liliana',
                'paternal_surname' => 'Fuentes',
                'maternal_surname' => 'Benavides',
                'dni' => '19870036',
                'email' => 'rfuentes@andino.edu.pe',
            ],
            [
                'first_name' => 'Ronaldo Plateni',
                'paternal_surname' => 'Pedraza',
                'maternal_surname' => 'Medrano',
                'dni' => '71383499',
                'email' => 'rpedraza@andino.edu.pe',
            ],
            [
                'first_name' => 'Rosa',
                'paternal_surname' => 'Mitma',
                'maternal_surname' => 'De La Cruz',
                'dni' => '20054706',
                'email' => 'rmitma@andino.edu.pe',
            ],
            [
                'first_name' => 'Rosario Elizabeth',
                'paternal_surname' => 'Llacua',
                'maternal_surname' => 'Antialon',
                'dni' => '20048205',
                'email' => 'rllacua@andino.edu.pe',
            ],
            [
                'first_name' => 'Ruth Graciela',
                'paternal_surname' => 'Cardenas',
                'maternal_surname' => 'Trujillo',
                'dni' => '75511856',
                'email' => 'rcardenas@andino.edu.pe',
            ],
            [
                'first_name' => 'Samuel',
                'paternal_surname' => 'Castillo',
                'maternal_surname' => 'Cuycapusa',
                'dni' => '20048574',
                'email' => 'scastillo@andino.edu.pe',
            ],
            [
                'first_name' => 'Samuel David',
                'paternal_surname' => 'Llanco',
                'maternal_surname' => 'Zavaleta',
                'dni' => '20105329',
                'email' => 'sllanco@andino.edu.pe',
            ],
            [
                'first_name' => 'Samuel Hector',
                'paternal_surname' => 'Figueroa',
                'maternal_surname' => 'Sanchez',
                'dni' => '19818626',
                'email' => 'sfigueroa@andino.edu.pe',
            ],
            [
                'first_name' => 'Saul Valeriano',
                'paternal_surname' => 'Santivañez',
                'maternal_surname' => 'Bernardo',
                'dni' => '19917000',
                'email' => 'ssantivanez@andino.edu.pe',
            ],
            [
                'first_name' => 'Smerly Brillith',
                'paternal_surname' => 'Torres',
                'maternal_surname' => 'Allca',
                'dni' => '71117273',
                'email' => 'storres@andino.edu.pe',
            ],
            [
                'first_name' => 'Soledad Ines',
                'paternal_surname' => 'Leon',
                'maternal_surname' => 'Gaspar',
                'dni' => '09842053',
                'email' => 'sleon@andino.edu.pe',
            ],
            [
                'first_name' => 'Thalia Araiza',
                'paternal_surname' => 'Sanchez',
                'maternal_surname' => 'Laura',
                'dni' => '48480971',
                'email' => 'tsanchez@andino.edu.pe',
            ],
            [
                'first_name' => 'Vanessa Nataly',
                'paternal_surname' => 'Jorge',
                'maternal_surname' => 'Egoavil',
                'dni' => '42566074',
                'email' => 'vjorge@andino.edu.pe',
            ],
            [
                'first_name' => 'Wilder Franco',
                'paternal_surname' => 'Davila',
                'maternal_surname' => 'Flores',
                'dni' => '20031450',
                'email' => 'wdavila@andino.edu.pe',
            ],
            [
                'first_name' => 'Yadira Brigith',
                'paternal_surname' => 'Lopez',
                'maternal_surname' => 'Martinez',
                'dni' => '74163086',
                'email' => 'ylopez@andino.edu.pe',
            ],
            [
                'first_name' => 'Yanett Laddy',
                'paternal_surname' => 'Flores',
                'maternal_surname' => 'Godiño',
                'dni' => '20106590',
                'email' => 'yflores@andino.edu.pe',
            ],
            [
                'first_name' => 'Yelhy Akemi',
                'paternal_surname' => 'Yancan',
                'maternal_surname' => 'Huaylinos',
                'dni' => '42947061',
                'email' => 'yyancan@andino.edu.pe',
            ],
            [
                'first_name' => 'Yesset',
                'paternal_surname' => 'Salome',
                'maternal_surname' => 'Yupari',
                'dni' => '75543123',
                'email' => 'ysalome@andino.edu.pe',
            ],
            [
                'first_name' => 'Yeyson Andy',
                'paternal_surname' => 'Huallullo',
                'maternal_surname' => 'Sanchez',
                'dni' => '72939759',
                'email' => 'yhuallullo@andino.edu.pe',
            ],
        ];
        // 4. Iteramos y creamos los usuarios de forma automática
        foreach ($usuarios as $userData) {
            $user = User::create([
                'first_name' => $userData['first_name'],
                'paternal_surname' => $userData['paternal_surname'],
                'maternal_surname' => $userData['maternal_surname'],
                'dni' => $userData['dni'],
                'email' => $userData['email'],
                'password' => Hash::make('asdasdasd'), // Contraseña por defecto para todos
                'status' => 'Activo',
            ]);

            // Asignamos el rol a cada uno
            $user->assignRole('Usuario');
        }
    }
}
