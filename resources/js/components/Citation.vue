<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 keep-height">
                <transition name="fade" mode="out-in">
                    <blockquote v-on:click="randomCitation" v-if="show" >
                        <p>{{ selectedCitation.message }}</p>

                        <footer>{{ selectedCitation.author }}</footer>

                    </blockquote>
                </transition>
                <!-- <button v-on:click="randomCitation">
                  reload
                </button> -->
            </div>
        </div>
    </div>
</template>

<script>
import { timeout } from 'q';
    export default {
        data() {
            return {
                show: false,
                citations: [
                    { message: 'Šikmo osvietené cesty, na ktorých vidno, že je večer a zima. Na nich postavy, pracujúci, unavení ľudia, možno pútnici, slabo osvetlení posledným večerným tieňom, bledí. Toto sa vo mne zrodilo, kým som prechádzal cez zoranú žltkastú zem na poľnú cestu. Oziminy sa v nej zelenajú, zvláštne, citlivo, ale nie nepríjemne, sa dotýkajú nôh človeka.', author: 'Ladislav Mednyánszky, 31. december 1880, Beckov' },
                    { message: 'Mnohokrát som prešiel týmto miestom, postál som a hlavou mi neprestajne vírilo, či vôbec treba túto krásnu krajinu pozorovať a preniesť do maľby. Hľadel som na zelenú trávu, modrú oblohu, plávajúce biele oblaky, pestrofarebné kvety a tmavé stromy. Videl som tú krajinu v plnom slnku, žiariacu v záplave farieb, a tiež pod závojom oblakov bez jediného tieňa...', author: 'Pál Munkácsy'},
                    { message: 'Videl som tú krajinu v plnom slnku, žiariacu v záplave farieb, a tiež pod závojom oblakov bez jediného tieňa: postavu, trávu, strom, krík, ako sa rozplývajú v jemnej farebnej harmónii. Avšak, je toto možné zobraziť? Smel by som? Totiž nikdy v živote som to takto namaľované nevidel. Či by som sa k tomu odhodlal práve ja, na ktorého diela každý hľadí s odstupom? Takto som sa trápil ďalej.', author: 'Pál Munkácsy'},
                    { message: 'Je to pozoruhodná krajina. Vôkol lesa Fontainebleau: osamelý ohromný dub – v letnej horúčave pod jeho lístím kostolný chlad. Keď príde jeseň, jeho rednúce listy šumia ako starobylý organ v Notre-Dame.', author: 'Pál Munkácsy'},
                    { message: 'Nové príjemné dojmy naplnili moju predstavivosť. Z okna som trikrát videl nádherné stimmungy oblohy (jedna z najjemnejších sivých), aké si len možno predstaviť.', author: 'Ladislav Mednyánszky, koniec januára 1878, Strážky'},
                    { message: 'Šikmo osvietené cesty, na ktorých vidno, že je večer a zima. Na nich postavy, pracujúci, unavení ľudia, možno pútnici, slabo osvetlení posledným večerným tieňom, bledí. Toto sa vo mne zrodilo, kým som prechádzal cez zoranú žltkastú zem na poľnú cestu. Oziminy sa v nej zelenajú, zvláštne, citlivo, ale nie nepríjemne, sa dotýkajú nôh človeka.', author: 'Ladislav Mednyánszky, 31. december 1880, Beckov'},
                    { message: 'Vážska dolina sa ponárala do čoraz tmavšej sivej hmly, len kde-tu sa vynoril jasný fliačik na kopci. Večerná zima bola taká, ako býva pred jarou. Obloha bola smerom na východ modrosivozelená, veľmi priesvitná – už viackrát videný efekt.', author: 'Ladislav Mednyánszky, 31. december 1880, Beckov'},
                    { message: 'Oblaky svietili, niektoré úzke mráčky, ktorých telá neboli do šarlátovej, mali teplú priesvitnú farbu. Oblaky s väčšími telami boli oveľa svetlejšie ako základ. Tento jav som nikdy dostatočne nepreštudoval, preto som sa naň pri jednoduchom dopoludňajšom svetle nikdy neodhodlal, je príliš svietivý a teplý.', author: 'Ladislav Mednyánszky, máj 1881, Beckov'},
                    { message: 'Tatry na mňa od prvej chvíle urobili hlboký dojem. Popoludňajšia cesta z Kežmarku na sandlauferi do Huncoviec a Lomnice na mňa vždy zapôsobila. Vychádzajúc z toho, prvé veci, ktoré som kreslil, boli vrchy, lode a ľudia. Tatry, ich nálada, ktorých znázornenie sa zdalo ako nemožné, sa pridali predstavou.', author: 'Ladislav Mednyánszky, pred rokom 1894'},
                    { message: 'Zázračný svet Mesiaca ma začal zaujímať v Strážkach. Dobrý, ale trocha suchý prozaický štýl som neskôr prekonal a mojím hlavným cieľom sa opäť stalo hľadanie nálady.', author: 'Ladislav Mednyánszky, pred rokom 1894'},
                    { message: 'Veľmi zvláštne pôsobenie videl som smerom na západ, so zadným osvetlením, vpredu bol sneh... Všetko doslova plávalo v zlatom prachu, obloha bola jasne modrozelená, trochu zosivená žltou.', author: 'Ladislav Mednyánszky, 20. apríl 1894, Strážky'},
                    { message: 'Rozhodol som sa opustiť Pilotyho školu a nasledovať od tejto chvíle už iba jediného učiteľa, ktorý ma bude viesť najlepším možným spôsobom, a tým učiteľom nie je nikto iný ako príroda.', author: 'Pál Szinyei Merse'},
                    { message: 'Ako vlastne treba vidieť túto krásnu prírodu a ako ju treba namaľovať na obraze? … zelenú pažiť, modrú oblohu, letiace biele oblaky, pestré kvety a tmavé stromy. Videl som krajinu skvieť sa vo farebnej nádhere prežiarenú slnkom, pod ľahkými oblakmi bez tieňa videl som splynúť do jemnej farebnej harmónie postavu, trávu, strom, krík.', author: 'Pál Szinyei Merse'},
                    { message: 'Keď trávim svoj čas na dedine, hlavne v čase, keď príroda si oblieka voľný odev, spomínam si na ráno, keď sa rosa trblietala na steblách trávy, nebo čisté a modré bolo, keď vzdušná hmla na jazere ležala, a ja som sa tmavomodrými tienistými lesmi prechádzal...', author: 'Emil Jacob Schindler'},
                    { message: 'Koukej na krajinu tak, jako poslouchá cvičený hudebník hudbu – vždy celou harmonii tónů – hlavně barvou – motiv co do formy musíš ihned v přírodě svobodně přizpůsobit k povaze krajiny, totiž volit sem tam ladnější linii, neb zas malichernost, která jen oko svádí od celkového lyrického dojmu, vynechat…', author: 'Antonín Chittussi '},
                    { message: 'Mé snažení jest sledovat cestu – tu pravou, kterou šli Rousseau, Daubigny, Corot a jiní. Umělci, jichž srdce hořelo láskou k přírodě, kteří jen tehdá, když lásky té byli plni, brali paletu do ruky... Byla to jen příroda, bez pitomých přívěsků, bez pravidel – procítěná srdcem čistým, která ty velké duchy vedla – a které jsem si z celé duše zamiloval.', author: 'Antonín Chittussi'},
                    { message: 'Já nemyslím na to, jak to dělám, nýbrž jaký to má účinek a hledím se úplně odloučit od všeho tradicionálního a starými pravidly zohyzděného. Já chci dojmout – to jest vše.', author: 'Antonín Chittussi '},
                    { message: 'Dnes ráno jsem si poprvé vyšla malovat. Došla jsem do krásné zahrady na hrádek k jeptiškám. Jsou tam překrásné velké stromy a našla bych tam ještě pěkné motivy a jeptišky jsou velmi vstřícné, pozvaly mne, abych znovu přišla.', author: 'Zdenka Braunerová'},
                    { message: 'Před čtyřmi dny jsem venku malovala krajinu u řeky. Ale už je trochu zima na malování, neboť jsem se nachladila. Nevím, jestli je dobré, co jsem udělala, je to zase s bílou trávou v prvním plánu, v mlze. Nevyznám se v malířství, nevím, jestli je to dobré... Opravdu myslím, že dělám velké pokroky...', author: 'Zdenka Braunerová'},
                    { message: 'Nadevšecko je krásná krajina u samého Londýna. Jezdíváme často parníkem z Belvederu do Londýna, což trvá asi půl druhé hodiny, a to je pravá pastva pro oči! Viděla jsem Londýn v dešti, tmavošedé, skoro fialové siluety domů a i při největším lijáku jemné, teplé, skoro zlaté nebe, což je plné poesie...', author: 'Zdenka Braunerová'},
                    { message: 'Obývám roztomilý pokojík v mansardě se starýma empire meublema a s vyhlídkou do polí. Objevila jsem nové motivy – samá voda, rákosí, hubené i košaté stromoví, daleké louky a modré horizonty!', author: 'Zdenka Braunerová'},
                    { message: 'Čekám netrpělivě na zajíce, nebo jinou zvěřinu. Budu se snažit eliminovat tahy štětce a malovat svědomitě, abych v tu dobu, za jeho přítomnosti, dosáhl uznání. Mám naději, že námět je podmínkou k úspěchu.', author: 'Soběslav Hippolyt Pinkas'},
                    { message: 'Toužím malovat stromy, skály, výhledy a nad nimi krásné oblaky, zkrátka celou tu velkolepost lesa.', author: 'Soběslav Hippolyt Pinkas'},
                    { message: 'Přírodu není třeba ani zkrášlovat, ani zesilovat. Má těchto vlastností sama dost. Važte si studia v přírodě. Kdybyste byli odsouzeni celý život ztráviti pouze na tomto kousku země, je možno z tohoto místa pokořit celý svět – uměním. Vše záleží jen na vás', author: 'Julius Mařák'}
                ],
                selectedCitation: { message: '', author: ''}
            };
        },

        methods : {
            async randomCitation() {
                this.show = false;
                const idx = Math.floor(Math.random() * this.citations.length);
                this.selectedCitation = this.citations[idx];
                setTimeout(function () {
                    this.show = true;
                }.bind(this), 500);

            }
        },

        mounted() {
            this.randomCitation()
        }
    }
</script>

<style scoped>

    blockquote {
        margin: 0;
        padding: 0;
        position: relative;
        /* width: 60%; */
        /* min-width: 400px; */
        /* max-width: 820px; */
        /* font-size: 1.25vw; */
        line-height: 1.4;
        text-rendering: optimizeLegibility;
        font-smoothing: antialiased;
        -webkit-font-smoothing: antialiased;
        -moz-osx-fon-smoothing: grayscale;
    }

    blockquote p:first-of-type:before {
        content: '\201c';
        position: absolute;
        left: -.5em;
    }

    blockquote p:last-of-type:after {
        content: '\201d';
        position: absolute;
    }

    blockquote footer {
        position: relative;
    }

    blockquote footer:before {
        content: '– ';
        position: absolute;
        left: -.6em;
    }

    @media screen and (max-width: 1600px) {
        blockquote {
            /* font-size: 2vw; */
        }
    }

    @media all and (max-width: 1200px) {
        blockquote {
            /* font-size: 2.5vw; */
        }
    }

    @media all and (max-width: 600px) {
        blockquote {
            /* font-size: 18px; */
        }
        blockquote {
            /* width: 100%; */
            /* min-width: 100%; */
        }
    }

    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
      opacity: 0;
    }

    .keep-height {
        min-height: 280px;
    }

    @media all and (min-width: 600px) {
        .keep-height {
            min-height: 130px;
        }
    }

</style>