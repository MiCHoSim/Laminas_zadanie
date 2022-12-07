<?php

namespace Micho\Form;

use App\AccountModul\Model\UserManager;
use App\ArticleModul\Model\ArticleManager;
use Micho\Exception\ValidationException;
use Micho\Utilities\StringUtilities;

/**
 ** Trieda Formulár služi na renderovanie jednotlivých kontroliek Formuláru
 ** Kontrolky z určitími názvami sa generujú podlašabloný, ine možeme pridavať samostatne
 * Class Formular
 * @package Micho\Formular
 */
class Form
{
    /**
     * Typy Formulárov
     */
    const TYPE_REGISTRATION = 'registration';
    const TYPE_FORGOTTEN_PASSWORD = 'forgotten_password';
    const TYPE_CHANGE_PASSWORD = 'change_password';

    /**
     * Zoznam Názvov Kontrolie, ktoré je možné generovaŤ automaticky
     */
    const NAME = 'name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const TEL= 'tel';
    const MESSAGE = 'message';
    const ANTISPAM = 'antispam';
    const CONSENT = 'consent';
    const STREET = 'street';
    const REGISTER_NUMBER = 'register_number';
    const CITY = 'city';
    const POSTCODE ='postcode';
    const COUNTRY_ID = 'country_id';
    const PASSWORD = 'password';
    const GENDER = 'gender';

    private $inputAtrClas;
    private $inputAtrClasLabel;
    private $checkRadioAtrClas;
    private $checkRadioAtrClasLabel;
    private $fileAtrClas;
    private $fileAtrClasLabel;

    /**
     * @var string
     */
    public $formId; // ID / nazov formulára kvoli generovaniu kontroliek mimo formulára ale taktiež kvoli ochrane => "CSRF" pri generovani tokenu konkretnému formuláru

    /**
     * @var array Pole kontroliek Fromulára
     */
    private $controls = array();
    private $sent = false;

    /**
     ** Konštuktor v ktorom automatický vygenerujem vŠetky kontrolky ktorých šablony mám uložené
     * Formular constructor.
     * @param string $formId ID / nazov formulára
     * @param array $controls Pole názvov Kontroliek Zo zoznamu kontorliek koré sa generujú automaticky, pretože sa Často popužívaju
     * @param string $inputAtrClas Nastavenie Triedy pre Input
     * @param string $inputAtrClasLabel Nastavenie Triedy pre Input Label
     * @param string $checkRadioAtrClas Nastavenie Triedy pre CheckRadio Label
     * @param string $checkRadioAtrClasLabel Nastavenie Triedy pre CheckRadio Label
     */
    public function __construct(string $formId, array $controls = array(), $inputAtrClas = 'form-control', $inputAtrClasLabel = 'sr-only', $checkRadioAtrClas = 'form-check-input', $checkRadioAtrClasLabel = 'form-check-label', $fileAtrClas = 'form-control-file', $fileAtrClasLabel = '')
    {
        $this->formId = $formId;
        $this->inputAtrClas = $inputAtrClas;
        $this->inputAtrClasLabel = $inputAtrClasLabel;
        $this->checkRadioAtrClas = $checkRadioAtrClas;
        $this->checkRadioAtrClasLabel = $checkRadioAtrClasLabel;
        $this->fileAtrClas = $fileAtrClas;
        $this->fileAtrClasLabel = $fileAtrClasLabel;

        foreach ($controls as $nameControl)
        {
            $nameMethod = 'set' . StringUtilities::underlineToCamel($nameControl,false);

            if(method_exists($this, $nameMethod))
                $this->$nameMethod(); //zavola metodú kontrolky, pomocov ktorej sa vytvorý kontrolka
        }
    }

    public function setInputAtrClas($atrClas)
    {
        $this->inputAtrClas = $atrClas;
    }
    public function setInputAtrClasLabel($atrClas)
    {
        $this->inputAtrClasLabel = $atrClas;
    }
    public function setCheckRadioAtrClas($atrClas)
    {
        $this->checkRadioAtrClas = $atrClas;
    }
    public function setCheckRadioAtrClasLabel($atrClas)
    {
        $this->checkRadioAtrClasLabel = $atrClas;
    }


    //----------------------------------- Predpripravene kontrolky -----------------------------------
    /**
     * Objekt Kontrolky - Input csrf  => ochrana proti útoku => "CSRF"
     */
    private function setCSRF()
    {
        if (!$this->sent)
        {
            $_SESSION['token'][$this->formId] = bin2hex(random_bytes(32)); // Generovanie autorizačného tokenu pre dany Formulár/Tlačidlo
        }

        $this->addInput('CSRF', 'csrf', Input::TYPE_HIDDEN, $_SESSION['token'][$this->formId]);
    }

    /**
     * Objekt Kontrolky - Input meno
     */
    private function setName()
    {
        $this->addInput('Name', self::NAME, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_STRING);
    }
    /**
     * Objekt Kontrolky - Input priezvisko
     */
    private function setLastName()
    {
        $this->addInput('Last name', self::LAST_NAME, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_STRING);
    }
    /**
     * Objekt Kontrolky - Input email
     */
    private function setEmail()
    {
        $this->addInput('Email',self::EMAIL, Input::TYPE_EMAIL,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_EMAIL);
    }
    /**
     * Objekt Kontrolky - Input tel
     */
    private function setTel()
    {
        $this->addInput('Telefón', self::TEL, Input::TYPE_TEL,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_TEL);
    }
    /**
     * Objekt Kontrolky - Input ulica
     */
    private function setStreet()
    {
        $this->addInput('Street', self::STREET, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_STRING);
    }
    /**
     * Objekt Kontrolky - Input supisne_cislo
     */
    private function setRegisterNumber()
    {
        $this->addInput('Register Number', self::REGISTER_NUMBER, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_REGISTER_NUMBER);
    }
    /**
     * Objekt Kontrolky - Input mesto
     */
    private function setCity()
    {
        $this->addInput('city', self::CITY, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true,Validator::PATTERN_STRING);
    }
    /**
     * Objekt Kontrolky - Input psc
     */
    private function setPostCode()
    {
        $this->addInput('Post code', self::POSTCODE, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_POSTCODE);
    }
    /**
     * Objekt Kontrolky - Input heslo
     */
    private function setPassword()
    {
        $this->addInput('Password', self::PASSWORD, Input::TYPE_PASSWORD,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_PASSWORD);
    }
    /**
     * Objekt Kontrolky - Radio pohlavie
     */
    private function setGender()
    {
        $this->addRadio(array('Male' => 'male', 'Female' => 'female'), self::GENDER);
    }
    /**
     * @return Select Objekt Kontrolky - Select Krajina
     */
    private function setCountryId()
    {
        $this->addSelect('Country', KrajinaManazer::KRAJINA_ID, array(), '', false,$this->inputAtrClas,$this->inputAtrClasLabel, true);
    }
    /**
     * @return TextArea Objekt Kontrolky - TextArea sprava
     */
    private function setMessage()
    {
        $this->addTextArea('Message', self::MESSAGE, 'Write your questions and requests here', '', '',3, $this->inputAtrClas, $this->inputAtrClasLabel, true);
    }
    /**
     * Objekt Kontrolky - Input antispam
     */
    private function setAntispam()
    {
        $this->addInput('Current year (AntiSpam)', self::ANTISPAM, Input::TYPE_TEXT,'', '', $this->inputAtrClas, $this->inputAtrClasLabel,true, Validator::PATTERN_ANTISPAM_YEAR);
    }
    /**
     * @return CheckBox Objekt Kontrolky - CheckBox suhlas
     */
    private function setConsent()
    {
        $clanokManazer = new ArticleManager();
        $spracovanieOsUD = $clanokManazer->vratClanok('ochrana-osobnych-udajov', array(ArticleManager::TITLE, ArticleManager::DESCRIPTION, ArticleManager::URL));
        $this->addCheckBox('súhlasím s: <a target="_blank" href="clanok/' . $spracovanieOsUD[ArticleManager::URL] . '">' . $spracovanieOsUD[ArticleManager::TITLE] . '</a>', self::CONSENT, 1,false, $spracovanieOsUD[ArticleManager::DESCRIPTION],'', $this->checkRadioAtrClas, $this->checkRadioAtrClasLabel, true);
    }

    //----------------------------------- Ine kontrolky -----------------------------------

    /**
     ** Vytvorí Novú kontrolku Input
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $type type - O aky typ kontrolky sa jedna text/number/email/tel,...
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param array|bool $pattern pattern - Patern/Vzor/Pravidlo pre kontrolku array(popis,pattern)
     * @param bool $disabled Či sa dá meniť hodnota prvku
     * @param string|bool $attributes Všetky ostatné atributy ktore chem naviše
     * @return void
     */
    public function addInput(string $name, string $nameDb, string $type , string $value = '', string $form = '', string $atrClas = '', string $atrClasLabel = '', bool $required = true, array|bool $pattern = false, bool $disabled = false, array|bool $attributes = false)
    {
        $atrClas = empty($atrClas) ? $this->inputAtrClas : $atrClas;
        $atrClasLabel = empty($atrClasLabel) ? $this->inputAtrClasLabel : $atrClasLabel;
        $this->controls[$nameDb] = new Input($name, $nameDb, $type , $value, $form, $atrClas, $atrClasLabel, $required, $pattern, $disabled, $attributes);
    }

    /**
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param array $options Pole možnosti kde klúč je názov a hodnota je Hodnota value
     * @param string $value Hodnota práve vybratého SELECTU
     * @param bool $multiple multiple - či je povoľený výber viacerých súborov
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function addSelect($name, $nameDb, array $options , $value = '', $multiple = false, $form = '', $atrClas = '', $atrClasLabel = '', $required = true, $attributes = false)
    {
        $atrClas = empty($atrClas) ? $this->inputAtrClas : $atrClas;
        $atrClasLabel = empty($atrClasLabel) ? $this->inputAtrClasLabel : $atrClasLabel;
        $this->controls[$nameDb] = new Select($name, $nameDb, $options, $value, $multiple, $form, $atrClas, $atrClasLabel, $required, $attributes);
    }

    /**
     ** Vytvorí Novú kontrolku CheckBox
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param false $checked checked - Či ma byť zaškrtnutý
     * @param string $title title - Popisok pre kontrolku
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function addCheckBox($name, $nameDb, $hodnota, $checked = false, $title = '', $form = '', $atrClas = '', $atrClasLabel = '', $required = true, $attributes = false)
    {
        $atrClas = empty($atrClas) ? $this->checkRadioAtrClas : $atrClas;
        $atrClasLabel = empty($atrClasLabel) ? $this->checkRadioAtrClasLabel : $atrClasLabel;
        $this->controls[$nameDb] = new CheckBox($name, $nameDb, $hodnota, $checked, $title, $form, $atrClas, $atrClasLabel, $required, $attributes);
    }
    /**
     * Vytvorý novú kontrolku radio Buttonov
     * @param array $nameValue Pole kde klúče su názvy Labelov a hodnoty Sú ich Hodnoty array (Label => value)
     * @param string $nameDb $nazovDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param false $checked checked - Či ma byť zaškrtnutý
     * @param string $title title - Popisok pre kontrolku
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function addRadio(array $nameValue, $nameDb, $checked = false, $title  = '', $form = '', $atrClas = '', $atrClasLabel = '', $required = true, $attributes = false)
    {
        $atrClas = empty($atrClas) ? $this->checkRadioAtrClas : $atrClas;
        $atrClasLabel = empty($atrClasLabel) ? $this->checkRadioAtrClasLabel : $atrClasLabel;
        foreach ($nameValue as $nazov => $hodnota)
        {
            $this->controls[$nameDb][$hodnota] = new Radio($nazov, $nameDb, $hodnota, $checked, $title, $form, $atrClas, $atrClasLabel, $required, $attributes);
        }
    }
    /**
     ** Vytvorí Novú kontrolku textArea
     * @param string $name label - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $placeholder placeholder - Placeholder pre Kontrolku
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param int $rows rows - Počet riadkov Kontrolky
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function addTextArea($name, $nameDb, $placeholder = '' , $value = '', $form = '', $rows = 3, $atrClas = '', $atrClasLabel = '', $required = true, $attributes = false)
    {
        $atrClas = empty($atrClas) ? $this->inputAtrClas : $atrClas;
        $atrClasLabel = empty($atrClasLabel) ? $this->inputAtrClasLabel : $atrClasLabel;
        $this->controls[$nameDb] = new TextArea($name, $nameDb, $placeholder, $value, $form, $rows, $atrClas, $atrClasLabel, $required, $attributes);
    }

    /**
     ** Vytvorí Novú kontrolku File
     * @param string $name label & placeholder - Názov pre kontrolku
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $atrClasLabel class - pre Label
     * @param bool $required required - či musí byť kontrolka vyplnená
     * @param bool $multiple multiple - či je povoľený výber viacerých súborov
     * @param string $accept accept - Povoľené formáty z možnosti Konštant
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function addFile($name, $nameDb, $form = '', $atrClas = '', $atrClasLabel = '', $required = true, $multiple = false, $accept = false, $attributes = false)
    {
        $atrClas = empty($atrClas) ? $this->fileAtrClas : $atrClas;
        $atrClasLabel = empty($atrClasLabel) ? $this->fileAtrClasLabel : $atrClasLabel;
        $this->controls[$nameDb] = new File($name, $nameDb, $form, $atrClas, $atrClasLabel, $required, $multiple, $accept, $attributes);
    }

    /**
     ** Vytvorí Nové Odosielacie Tlačidlo
     * @param string $value value - Hodnota ktorú ma kontrolka vyplnenú -> Názov Tlačidla
     * @param string $nameDb name & id - Kontrolky, popripade názov ako je ulozená hodnota v DB
     * @param string $form form - V prípade ze je kontrolka mimo formulára treba udať ku ktorému formuláru patrý
     * @param string $atrClas class - Trieda kontrolky
     * @param string $attributes Všetky ostatné atributy ktore chem naviše
     */
    public function addSubmit($value, $nameDb, $form = '', $atrClas = 'btn btn-lg btn-outline-danger btn-block', $attributes = false)
    {
        $this->controls[$nameDb] = new Submit($value, $nameDb, $form, $atrClas, $attributes);

        if ($_POST && isset($_POST[$nameDb]))
        {
            $this->sent = true;
        }

        $this->setCSRF(); // vytvory kontrolku => ochrana proty útoku => "CSRF"

    }

    //----------------------------------- Vrátenie a nastevenie kontroliek kontrolkky -----------------------------------

    /**
     * @return array Vráti pole HTML všetkých kontroliek formulára
     */
    public function createForm()
    {
        $form['form_id'] = $this->formId;

        foreach (array_keys($this->controls) as $name)
        {
            if(is_object($this->controls[$name]))
                $form[$name] = $this->controls[$name]->createControl();
            else// ak nieje objekt znamená to, že pracujem z Radio ktorý je v kontorlke uložený ako Názov DB a podtým array(moznosti výberu)
            {   // $nazov array(polozky1, polozka2,...)
                foreach (array_keys($this->controls[$name]) as $subname)
                {
                    $form[$name][$subname] = $this->controls[$name][$subname]->createControl();
                }
            }
        }
        return $form;
    }

    /**
     ** Upravi parametre kontrolky
     * @param string $control Názov kontrolky
     * @param array $parameters Nové parametre
     */
    public function editControlParameters($control, array $parameters)
    {
        $this->controls[$control]->editParameters($parameters);
    }

    /**
     ** Vratí povolené dáta z formulára
     * @param $filesName Názov Kontorlky ktorá obsahuje File
     * @return array
     */
    public function getData($filesName = false): array
    {
        array_pop($_POST); // Odstránenie údajov tlačidla
        array_shift($_POST); // Odstránenie údajov csrf

        return $this->checkValues($filesName);


    }

    /**
     ** Ošetrý a vráti hodnoty získane z $_POST a $_FILE => ochrana proti útoku => "Mass assignment"
     * @param string $filesName Názov Kontorlky ktorá obsahuje File
     * @return array ošetrené hodnoty z $_POST a $_FILE
     */
    private function checkValues($filesName = false): array
    {
        $formData = array_intersect_key($_POST, $this->controls); // orezanie údajov iba na potrebné hodnoty
        if ($filesName)
        {
            $formData[$filesName] = $_FILES[$filesName]; // orezanie údajov iba na potrebné hodnoty
        }
        return $formData;
    }

    /**
     ** Nastavý hodnoty kontrolkám
     * @param array $formData Pole Hodnôt
     */
    public function setValuesControls($formData)
    {
        foreach ($formData as $name => $value)
        {
            if(isset($this->controls[$name]))
            {
                if($this->controls[$name] instanceof CheckBox) // Nastaveni Hodnoty, Teda zaškrutnutie políčka
                {
                    if ($value)
                        $this->controls[$name]->checked();
                }
                elseif(is_object($this->controls[$name])) //Nastavenie hodnoty pre kontrolky ktorým sa hodnotá zadáva ručne
                    $this->controls[$name]->editParameters(array('value' => $value));

                else // ak nieje objekt znamená to, že pracujem z Radio ktorý je v kontorlke uložený ako Názov DB a podtým array(moznosti výberu)
                    // $nazov array(polozky1, polozka2,...)
                    $this->controls[$name][$value]->checked();
            }
        }
    }

    /**
     ** Zvaliduje údaje od uzivateľa
     * @param array $data Dáta od uzivateľa
     * @param false $type Typ formulára ktory kontrolujem, co mam prizeraŤ
     * @return void
     * @throws ValidationException
     * @throws \ErrorException
     */
    public function validate($data, $type = false)
    {
        $messages = array();

        foreach ($data as $name => $value)
        {
            //radio Button nekontorlujem ak nieje objekt znamená to, že pracujem z Radio ktorý je v kontorlke uložený ako Názov DB a podtým array(moznosti výberu)
            if (is_object($this->controls[$name]) && $this->controls[$name]->returnParameter(Controls::REQUIRED)) // overenie či je hodnota vyplnená ak má byť vyplnená
            {
                if (($this->controls[$name] instanceof File && empty($data[$name]['name'][0])) || (empty($data[$name]) && $data[$name] === false))
                    throw new ValidationException('Validation errors occurred', 0, null, array('Not all required values were filled
'));
            }

            if ($name === self::ANTISPAM) // overujem zhodu antispamu
            {
                $antispam = new AntispamRok();
                $messages[] = $antispam->over($data[self::ANTISPAM]);
            }
            elseif (is_object($this->controls[$name]) && method_exists($this->controls[$name], 'returnPattern')) // validovanie Kontroliek ktore maju pattern
            {
                $vzor = $this->controls[$name]->returnPattern();
                if ($vzor && !preg_match('/^' . $vzor . '/',$value))
                    $messages[] = 'Incorrectly entered value: ' . $this->controls[$name]->returnParameter('name');
            }
            elseif ($this->controls[$name] instanceof CheckBox) // validovanie Kontolky CheckBox na hodnoty ktoré má uložené v Hodnote
            {
                $hodnotaCheckboxu = $this->controls[$name]->returnParameter(Select::VALUE);
                if ($data[$name] != $hodnotaCheckboxu)
                    throw new ValidationException('Validation errors occurred', 0, null, array('Control: ' . $this->controls[$name]->returnParameter('name') . ' has a false value'));

            }
            elseif ($this->controls[$name] instanceof Select) // validovanie Kontolky Select na hodnoty ktoré am v options
            {
                $moznosti = $this->controls[$name]->returnParameter(Select::OPTIONS);

                if ($this->controls[$name]->returnParameter(Select::MULTIPLE)) // pre multiple select prechaddam polozku po polozke
                {
                    foreach ($data[$name] as $moznost)
                    {
                        if (!in_array($moznost, $moznosti))
                            throw new ValidationException('Validation errors occurred', 0, null, array('The selected value for the indicator: ' . $this->controls[$name]->returnParameter('name') . ' does not exist'));
                    }
                }
                elseif (!in_array($data[$name], $moznosti))
                    throw new ValidationException('Validation errors occurred', 0, null, array('The selected value for the indicator: ' . $this->controls[$name]->returnParameter('name') . ' does not exist'));
            }
            elseif ($this->controls[$name] instanceof File && $this->controls[$name]->returnParameter(File::ACCEPT)) // validovanie Kontolky typu file na akceptovaný formát
            {
                $akceptovaneTypPole = explode('/', $this->controls[$name]->returnParameter(File::ACCEPT)); // rozdelenie akceptovania podla lomitka

                if (is_array($data[$name]['tmp_name'])) // ak je pole prejdem vsetky polozky  akontoluje mich format
                {
                    foreach ($data[$name]['tmp_name'] as $subor)  // prejdenie vŠetkých suborov
                    {
                        $typPole = explode('/', mime_content_type($subor)); // zistenie typu suboru a rozdelenie ho podla lomitka

                        if ($typPole[0] !== $akceptovaneTypPole[0]) // ak sa nezhoduje prvá Časť vyvolám vynimku
                            throw new ValidationException('Validation errors occurred', 0, null, array('The selected files do not have an allowed format'));

                        if($akceptovaneTypPole[1] !== '*' && $typPole[1] !== $akceptovaneTypPole[1]) // ak sa druháčast nerovna "*" tak porovnávam aj druhu
                            throw new ValidationException('Validation errors occurred', 0, null, array('The selected files do not have an allowed format'));
                    }
                }
                else // kontrolujem format iba toho jedneho obrazka
                {
                    $typPole = explode('/', mime_content_type($data[$name]['tmp_name'])); // zistenie typu suboru a rozdelenie ho podla lomitka

                    if ($typPole[0] !== $akceptovaneTypPole[0]) // ak sa nezhoduje prvá Časť vyvolám vynimku
                        throw new ValidationException('Validation errors occurred', 0, null, array('The selected files do not have an allowed format'));

                    if($akceptovaneTypPole[1] !== '*' && $typPole[1] !== $akceptovaneTypPole[1]) // ak sa druháčast nerovna "*" tak porovnávam aj druhu
                        throw new ValidationException('NValidation errors occurred', 0, null, array('The selected files do not have an allowed format'));
                }

            }
        }

        $messages = array_filter($messages);

        if(!empty($messages)) // ak nieje pole prázdne tak vyvolam vynimku na vypísanie správ
            throw new ValidationException('Validation errors occurred', 0, null, $messages);
    }

    /**
     ** Vráti Klúče/Názvy Kontorliek
     * @param false $button ci chem vrátit ak tlacidlo
     * @return array $klúče
     */
    public function returnKeysControls($button = false)
    {
        $keys = array_keys($this->controls);
        array_shift($keys); // odstranenie kontrolky csrf
        if (!$button)
            array_pop($keys);
        return $keys;
    }


    /**
     * Zisti či je možne začat spracovávať data fomulára
     * @return bool
     */
    public function dataProcesing(): bool
    {
        return $this->sent() && $this->checkCSRF();
    }

    /**
     * @return bool Či bolo stlačené tlačidlo na odoslanie formulára
     */
    private function sent(): bool
    {
        return $this->sent;
    }

    /**
     ** overi ci je splnená podmineika tokenu pre "CSRF"
     * @return bool
     */
    private function checkCSRF(): bool
    {
        if(!empty($_POST['csrf'])) // pripomienka pre programatora Že nastava bezpečnostná chyba typu CSRF
        {
            if (hash_equals($_SESSION['token'][$this->formId], $_POST['csrf'])) // porovnanie tokenov
            {
                return true;
            }
        }
        return false;
    }

}
/*
 * Autor: MiCHo
 */
