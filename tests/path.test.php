<?php

declare(strict_types=1);

use Path\Path;

require __DIR__.'/../vendor/autoload.php';

$attemp = 0;

$cwd = \getcwd();

function compare(string $compare, string $to, ?string $onFailed = null, ?string $onSuccess = null)
{
  global $attemp;
  $attemp++;
  $i = $attemp < 10 ? '0' . $attemp : $attemp;
  $onSuccess = $onSuccess ?? "\033[38;2;242;141;26m". $i.". Test passed ✓ \033[0m";
  $onFailed  = $onFailed ?? "\033[38;2;201;13;13m". $i.'. Failed test ✗';
  echo ($compare !== $to ? $onFailed : $onSuccess)."\n";
  sleep(1);
}

$resolve_source = [
$cwd.'\\src\\components\\Button\\index.js\\styles\\main.css\\themes\\dark\\assets\\images\\logos\\logo.png' => Path::resolve('src///components///Button///index.js', 'styles///main.css', 'themes///dark', 'assets///images', 'logos///logo.png'),
$cwd.'\\src\\components\\Card\\index.js\\styles\\card.css\\themes\\light\\assets\\images\\icons\\icon.svg' => Path::resolve('src///components///Card///index.js', 'styles///card.css', 'themes///light', 'assets///images', 'icons///icon.svg'),
$cwd.'\\src\\utils\\helpers\\dateUtils\\formatDate.js\\validators\\emailValidator.js\\parsers\\csvParser.js\\formatters\\jsonFormatter.js\\constants\\appConstants.js' => Path::resolve('src///utils///helpers///dateUtils///formatDate.js', 'validators///emailValidator.js', 'parsers///csvParser.js', 'formatters///jsonFormatter.js', 'constants///appConstants.js'),
$cwd.'\\src\\services\\api\\user\\v1\\index.js\\models\\UserModel.js\\controllers\\UserController.js\\middlewares\\authMiddleware.js\\utils\\apiUtils.js' => Path::resolve('src///services///api///user///v1///index.js', 'models///UserModel.js', 'controllers///UserController.js', 'middlewares///authMiddleware.js', 'utils///apiUtils.js'),
$cwd.'\\src\\config\\defaults\\settings\\userSettings.json\\env\\development.env\\env\\production.env\\db\\config.js\\api\\apiConfig.json' => Path::resolve('src///config///defaults///settings///userSettings.json', 'env///development.env', 'env///production.env', 'db///config.js', 'api///apiConfig.json'),
$cwd.'\\src\\controllers\\user\\profile\\controller\\getProfile.js\\services\\profileService.js\\utils\\profileUtils.js\\validations\\profileValidation.js\\constants\\profileConstants.js' => Path::resolve('src///controllers///user///profile///controller///getProfile.js', 'services///profileService.js', 'utils///profileUtils.js', 'validations///profileValidation.js', 'constants///profileConstants.js'),
$cwd.'\\src\\models\\User\\profile\\schema\\userProfileSchema.js\\helpers\\schemaHelpers.js\\validations\\schemaValidations.js\\constants\\schemaConstants.js\\migrations\\createUserProfile.js' => Path::resolve('src///models///User///profile///schema///userProfileSchema.js', 'helpers///schemaHelpers.js', 'validations///schemaValidations.js', 'constants///schemaConstants.js', 'migrations///createUserProfile.js'),
$cwd.'\\src\\views\\user\\profile\\details\\index\\summary.html\\components\\ProfileCard.js\\components\\ProfileEditForm.js\\styles\\profileStyles.css\\assets\\profilePics' => Path::resolve('src///views///user///profile///details///index///summary.html', 'components///ProfileCard.js', 'components///ProfileEditForm.js', 'styles///profileStyles.css', 'assets///profilePics'),
$cwd.'\\public\\assets\\images\\logos\\dark\\logo.png\\public\\assets\\images\\backgrounds\\main-bg.jpg\\public\\assets\\fonts\\Roboto\\Roboto-Regular.ttf\\public\\assets\\videos\\intro.mp4\\public\\uploads\\temp' => Path::resolve('public///assets///images///logos///dark///logo.png', 'public///assets///images///backgrounds///main-bg.jpg', 'public///assets///fonts///Roboto///Roboto-Regular.ttf', 'public///assets///videos///intro.mp4', 'public///uploads///temp'),
$cwd.'\\tests\\unit\\components\\Button\\Button\\button.test.js\\tests\\unit\\components\\Card\\Card\\card.test.js\\tests\\integration\\services\\api\\user\\v1\\userApi.test.js\\tests\\mocks\\mockData.js\\tests\\e2e\\userFlow.test.js' => Path::resolve('tests///unit///components///Button///Button///button.test.js', 'tests///unit///components///Card///Card///card.test.js', 'tests///integration///services///api///user///v1///userApi.test.js', 'tests///mocks///mockData.js', 'tests///e2e///userFlow.test.js'),
$cwd.'\\scripts\\build\\webpack\\config\\prod\\webpack.config.js\\scripts\\build\\webpack\\loaders\\babelLoader.js\\scripts\\build\\webpack\\plugins\\htmlWebpackPlugin.js\\scripts\\build\\webpack\\devServer.js\\scripts\\build\\scripts\\build.js' => Path::resolve('scripts///build///webpack///config///prod///webpack.config.js', 'scripts///build///webpack///loaders///babelLoader.js', 'scripts///build///webpack///plugins///htmlWebpackPlugin.js', 'scripts///build///webpack///devServer.js', 'scripts///build///scripts///build.js'),
$cwd.'\\lib\\express\\middleware\\auth\\jwt\\auth.js\\lib\\express\\middleware\\logging\\logger.js\\lib\\express\\middleware\\errorHandling\\errorHandler.js\\lib\\express\\routes\\userRoutes.js\\lib\\express\\routes\\adminRoutes.js' => Path::resolve('lib///express///middleware///auth///jwt///auth.js', 'lib///express///middleware///logging///logger.js', 'lib///express///middleware///errorHandling///errorHandler.js', 'lib///express///routes///userRoutes.js', 'lib///express///routes///adminRoutes.js'),
$cwd.'\\routes\\api\\v1\\user\\profile\\index\\update.js\\routes\\api\\v1\\user\\profile\\index\\delete.js\\routes\\api\\v1\\user\\profile\\index\\get.js\\routes\\api\\v1\\admin\\adminRoutes.js\\routes\\api\\v1\\auth\\authRoutes.js' => Path::resolve('routes///api///v1///user///profile///index///update.js', 'routes///api///v1///user///profile///index///delete.js', 'routes///api///v1///user///profile///index///get.js', 'routes///api///v1///admin///adminRoutes.js', 'routes///api///v1///auth///authRoutes.js'),
$cwd.'\\locales\\en\\translations\\user\\profile\\translation.json\\locales\\es\\translations\\user\\profile\\translation.json\\locales\\fr\\translations\\user\\profile\\translation.json\\locales\\en\\translations\\common\\translation.json\\locales\\es\\translations\\common\\translation.json' => Path::resolve('locales///en///translations///user///profile///translation.json', 'locales///es///translations///user///profile///translation.json', 'locales///fr///translations///user///profile///translation.json', 'locales///en///translations///common///translation.json', 'locales///es///translations///common///translation.json')
];

echo "Start resolver debugging \n";
foreach($resolve_source as $compare => $to) {
  compare($compare, $to);
}
echo "Resolver done!\n";
$attemp = 0;

sleep(1);

$relative_source = [
'..\\Card\\styles\\card.css'=>Path::relative('src///components///Button', 'src///components///Card///styles///card.css'),
'dateUtils\\formatDate.js'=>Path::relative('src///utils///helpers', 'src///utils///helpers///dateUtils///formatDate.js'),
'..\\..\\product\\v1\\index.js'=>Path::relative('src///services///api///user///v1', 'src///services///api///product///v1///index.js'),
'settings\\userSettings.json'=>Path::relative('src///config///defaults', 'src///config///defaults///settings///userSettings.json'),
'..\\settings\\updateProfile.js'=>Path::relative('src///controllers///user///profile', 'src///controllers///user///settings///updateProfile.js'),
'..\\settings\\userSettings.js'=>Path::relative('src///models///User///profile', 'src///models///User///settings///userSettings.js'),
'..\\edit\\index.html'=>Path::relative('src///views///user///profile///details', 'src///views///user///profile///edit/index.html'),
'..\\backgrounds\\main-bg.jpg'=>Path::relative('public///assets///images///logos', 'public///assets///images///backgrounds///main-bg.jpg'),
'..\\Card\\Card.test.js'=>Path::relative('tests///unit///components///Button', 'tests///unit///components///Card///Card.test.js'),
'config\\prod\\webpack.config.js'=>Path::relative('scripts///build///webpack', 'scripts///build///webpack///config///prod///webpack.config.js'),
'..\\errorHandling\\errorHandler.js'=>Path::relative('lib///express///middleware///auth', 'lib///express///middleware///errorHandling///errorHandler.js'),
'..\\product\\index.js'=>Path::relative('routes///api///v1///user', 'routes///api///v1///product///index.js'),
'..\\product\\translation.json'=>Path::relative('locales///en///translations///user', 'locales///en///translations///product///translation.json'),
'Dropdown\\index.jsx'=>Path::relative('src///components///Header///NavBar', 'src///components///Header///NavBar///Dropdown///index.jsx'),
'custom\\index.js'=>Path::relative('src///hooks///useFetch', 'src///hooks///useFetch///custom///index.js'),
'providers\\UserContextProvider.js'=>Path::relative('src///context///UserContext', 'src///context///UserContext///providers///UserContextProvider.js'),
'light\\global.css'=>Path::relative('src///styles///global///themes', 'src///styles///global///themes///light/global.css'),
'..\\OpenSans\\weights\\OpenSans-Regular.ttf'=>Path::relative('src///assets///fonts///Roboto', 'src///assets///fonts///OpenSans///weights///OpenSans-Regular.ttf'),
'..\\..\\reducers\\userReducer\\index.js'=>Path::relative('src///redux///actions///userActions', 'src///redux///reducers///userReducer///index.js'),
'..\\productData\\mockProductData.js'=>Path::relative('src///tests///mocks///userData', 'src///tests///mocks///productData/mockProductData.js'),
'..\\reference\\endpoints.md'=>Path::relative('src///docs///API///overview', 'src///docs///API///reference/endpoints.md'),
'..\\stringUtils\\capitalize.js'=>Path::relative('src///helpers///arrayUtils', 'src///helpers///stringUtils///capitalize.js'),
'..\\migrations\\createTables.js'=>Path::relative('src///services///database///queries', 'src///services///database///migrations/createTables.js'),
'..\\v1\\user\\profile\\index.js'=>Path::relative('src///routes///api///v2', 'src///routes///api///v1/user/profile/index.js'),
'..\\logos\\dark\\logo.png'=>Path::relative('src///assets///images///backgrounds', 'src///assets///images///logos/dark/logo.png'),
'..\\headers.js'=>Path::relative('src///config///api///endpoints', 'src///config///api///headers.js'),
'Header\\index.js'=>Path::relative('src///components///Layout', 'src///components///Layout///Header/index.js'),
'logger.js'=>Path::relative('src///middleware', 'src///middleware///logger.js'),
'store.js'=>Path::relative('src///redux', 'src///redux///store.js'),
'e2e\\userFlow.test.js'=>Path::relative('src///tests', 'src///tests///e2e/userFlow.test.js')
];

echo "Start relative debugging \n";
foreach($relative_source as $compare => $to) {
  compare($compare, $to);
}
echo "Relative done!\n";
?>