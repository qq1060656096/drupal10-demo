/*
intl-tel-input
version: 0.8.3
description: A jQuery plugin for entering international telephone numbers
repository: https://github.com/Bluefieldscom/intl-tel-input.git
license: MIT
author: Jack O'Connor (http://jackocnr.com)

*/


(function ($) {
  $(document).ready(
    function () {
      resize_phone_field();
      jQuery(".query_phone").intlTelInput();

      $('#query_phone').keyup(function () {
        var v = document.getElementById('query_phone--description');
        var mo_2fa_phone = document.getElementById('query_phone').value;
        if (mo_2fa_phone == '')
          document.getElementById('query_phone').value = '+';
        if (search(mo_2fa_phone.substring(1, 2)) + search(mo_2fa_phone.substring(1, 3)) + search(mo_2fa_phone.substring(1, 4)) + search(mo_2fa_phone.substring(1, 5)) == 0) {
          v.textContent = "select/type correct countryCode";
          v.style.color = 'pink';

        } else {

          v.textContent = "Enter number with country code Eg. +00xxxxxxxxxx";
          v.style.color = 'green';

        }
      });

    },
  );
}(jQuery));


function resize_phone_field() {
  var phone_field_arr = document.getElementsByClassName('query_phone');
  for (let i = 0; i < phone_field_arr.length; i++) {
    phone_field_arr[i].size = 55;
  }

}

!function (a, b, c) {
  function d(b, c) {
    this.element = b, this.options = a.extend({}, f, c), this._defaults = f, this._name = e, this.init()
  }

  var e = "intlTelInput", f = {preferredCountries: ["IN", "US"], americaMode: false};
  d.prototype = {
    init: function () {
      var b = this, d = [];
      a.each(this.options.preferredCountries, function (b, c) {
        var e = a.grep(intlTelInput.countries, function (a) {
          return a.cca2 == c
        });
        e.length && d.push(e[0])
      });
      var e = a(this.element);
      "" !== e.val() || this.options.americaMode || e.val("+1 "), e.wrap(a("<div>", {"class": "intl-number-input"}));
      var f = a("<div>", {"class": "flag-dropdown f16"}).insertBefore(e),
        g = a("<div>", {"class": "selected-flag"}).appendTo(f), h = d[0].cca2.toLowerCase(),
        i = a("<div>", {"class": "flag " + h}).appendTo(g);
      a("<div>", {"class": "down-arrow"}).appendTo(i);
      var j = a("<ul>", {"class": "country-list hide"}).appendTo(f);
      this.appendListItems(d, j), a("<li>", {"class": "divider"}).appendTo(j), this.appendListItems(intlTelInput.countries, j);
      var k = j.children(".country");
      k.first().addClass("active"), e.keyup(function () {
        var c = b.getDialCode(e.val()) || "1", d = intlTelInput.countryCodes[c], f = !1;
        if (a.each(d, function (a, b) {
          i.hasClass(b.toLowerCase()) && (f = !0)
        }), !f) {
          var g = intlTelInput.countryCodes[c][0].toLowerCase();
          i.attr("class", "flag " + g), k.removeClass("active"), k.children(".flag." + g).parent().addClass("active")
        }
      }), e.keyup(), g.click(function (d) {
        if (d.stopPropagation(), j.hasClass("hide")) {
          k.removeClass("highlight");
          var f = j.children(".active").addClass("highlight");
          b.scrollTo(f, j), j.removeClass("hide"), a(c).bind("keydown.intlTelInput", function (c) {
            if (38 == c.which || 40 == c.which) {
              var d = j.children(".highlight").first(), f = 38 == c.which ? d.prev() : d.next();
              f && (f.hasClass("divider") && (f = 38 == c.which ? f.prev() : f.next()), k.removeClass("highlight"), f.addClass("highlight"), b.scrollTo(f, j))
            } else if (13 == c.which) {
              var h = j.children(".highlight").first();
              h.length && b.selectCountry(h, g, e, j)
            } else if (9 == c.which || 27 == c.which) b.closeDropdown(j); else if (c.which >= 97 && c.which <= 122 || c.which >= 65 && c.which <= 90) {
              var i = String.fromCharCode(c.which), l = k.filter(function () {
                return a(this).text().charAt(0) == i
              });
              if (l.length) {
                var m, n = l.filter(".highlight").first();
                m = n && n.next() && n.next().text().charAt(0) == i ? n.next() : l.first(), k.removeClass("highlight"), m.addClass("highlight"), b.scrollTo(m, j)
              }
            }
          })
        } else b.closeDropdown(j)
      }), k.mouseover(function () {
        k.removeClass("highlight"), a(this).addClass("highlight")
      }), k.click(function (c) {
        var d = a(c.currentTarget);
        b.selectCountry(d, g, e, j)
      }), a("html").click(function (c) {
        a(c.target).closest(".country-list").length || b.closeDropdown(j)
      })
    }, selectCountry: function (a, b, c, d) {
      var e = a.attr("data-country-code").toLowerCase();
      b.find(".flag").attr("class", "flag " + e);
      var f = this.updateNumber(c.val(), a.attr("data-dial-code"));
      c.val(f), this.closeDropdown(d), c.focus(), d.children(".country").removeClass("active highlight"), a.addClass("active")
    }, closeDropdown: function (b) {
      b.addClass("hide"), a(c).unbind("keydown.intlTelInput")
    }, scrollTo: function (a, b) {
      var c = b.height(), d = b.offset().top, e = d + c, f = a.outerHeight(), g = a.offset().top, h = g + f,
        i = g - d + b.scrollTop();
      if (d > g) b.scrollTop(i); else if (h > e) {
        var j = c - f;
        b.scrollTop(i - j)
      }
    }, updateNumber: function (a, b) {
      var c, d = "+" + this.getDialCode(a), e = "+" + b;
      return d.length > 1 ? (c = a.replace(d, e), a == d && (c += " ")) : c = a.length && "+" != a.substr(0, 1) ? e + " " + a.trim() : e + " ", this.options.americaMode && "+1 " == c.substring(0, 3) && (c = c.substring(3)), c
    }, getDialCode: function (a) {
      var b = a.trim().split(" ")[0];
      if ("+" == b.substring(0, 1)) for (var c = b.replace(/\D/g, "").substring(0, 4), d = c.length; d > 0; d--) if (c = c.substring(0, d), intlTelInput.countryCodes[c]) return c;
      return ""
    }, appendListItems: function (b, c) {
      var d = "";
      a.each(b, function (a, b) {
        d += "<li class='country' data-dial-code='" + b["calling-code"] + "' data-country-code='" + b.cca2 + "'>", d += "<div class='flag " + b.cca2.toLowerCase() + "'></div>", d += "<span class='country-name'>" + b.name + "</span>", d += "<span class='dial-code'>+" + b["calling-code"] + "</span>", d += "</li>"
      }), c.append(d)
    }
  }, a.fn[e] = function (b) {
    return this.each(function () {
      a.data(this, "plugin_" + e) || a.data(this, "plugin_" + e, new d(this, b))
    })
  }
}(jQuery, window, document);
var intlTelInput = {
  countries: [{name: "Afghanistan", cca2: "AF", "calling-code": "93"}, {
    name: "Albania",
    cca2: "AL",
    "calling-code": "355"
  }, {name: "Algeria", cca2: "DZ", "calling-code": "213"}, {
    name: "American Samoa",
    cca2: "AS",
    "calling-code": "1684"
  }, {name: "Andorra", cca2: "AD", "calling-code": "376"}, {
    name: "Angola",
    cca2: "AO",
    "calling-code": "244"
  }, {name: "Anguilla", cca2: "AI", "calling-code": "1264"}, {
    name: "Antigua and Barbuda",
    cca2: "AG",
    "calling-code": "1268"
  }, {name: "Argentina", cca2: "AR", "calling-code": "54"}, {
    name: "Armenia",
    cca2: "AM",
    "calling-code": "374"
  }, {name: "Aruba", cca2: "AW", "calling-code": "297"}, {
    name: "Australia",
    cca2: "AU",
    "calling-code": "61"
  }, {name: "Austria", cca2: "AT", "calling-code": "43"}, {
    name: "Azerbaijan",
    cca2: "AZ",
    "calling-code": "994"
  }, {name: "Bahamas", cca2: "BS", "calling-code": "1242"}, {
    name: "Bahrain",
    cca2: "BH",
    "calling-code": "973"
  }, {name: "Bangladesh", cca2: "BD", "calling-code": "880"}, {
    name: "Barbados",
    cca2: "BB",
    "calling-code": "1246"
  }, {name: "Belarus", cca2: "BY", "calling-code": "375"}, {
    name: "Belgium",
    cca2: "BE",
    "calling-code": "32"
  }, {name: "Belize", cca2: "BZ", "calling-code": "501"}, {
    name: "Benin",
    cca2: "BJ",
    "calling-code": "229"
  }, {name: "Bermuda", cca2: "BM", "calling-code": "1441"}, {
    name: "Bhutan",
    cca2: "BT",
    "calling-code": "975"
  }, {name: "Bolivia", cca2: "BO", "calling-code": "591"}, {
    name: "Bosnia and Herzegovina",
    cca2: "BA",
    "calling-code": "387"
  }, {name: "Botswana", cca2: "BW", "calling-code": "267"}, {
    name: "Brazil",
    cca2: "BR",
    "calling-code": "55"
  }, {name: "Brunei Darussalam", cca2: "BN", "calling-code": "673"}, {
    name: "Bulgaria",
    cca2: "BG",
    "calling-code": "359"
  }, {name: "Burkina Faso", cca2: "BF", "calling-code": "226"}, {
    name: "Burundi",
    cca2: "BI",
    "calling-code": "257"
  }, {name: "Cambodia", cca2: "KH", "calling-code": "855"}, {
    name: "Cameroon",
    cca2: "CM",
    "calling-code": "237"
  }, {name: "Canada", cca2: "CA", "calling-code": "1"}, {
    name: "Cape Verde",
    cca2: "CV",
    "calling-code": "238"
  }, {name: "Cayman Islands", cca2: "KY", "calling-code": "1345"}, {
    name: "Central African Republic",
    cca2: "CF",
    "calling-code": "236"
  }, {name: "Chad", cca2: "TD", "calling-code": "235"}, {
    name: "Chile",
    cca2: "CL",
    "calling-code": "56"
  }, {name: "China", cca2: "CN", "calling-code": "86"}, {
    name: "Colombia",
    cca2: "CO",
    "calling-code": "57"
  }, {name: "Comoros", cca2: "KM", "calling-code": "269"}, {
    name: "Congo (DRC)",
    cca2: "CD",
    "calling-code": "243"
  }, {name: "Congo (Republic)", cca2: "CG", "calling-code": "242"}, {
    name: "Cook Islands",
    cca2: "CK",
    "calling-code": "682"
  }, {name: "Costa Rica", cca2: "CR", "calling-code": "506"}, {
    name: "Côte d'Ivoire",
    cca2: "CI",
    "calling-code": "225"
  }, {name: "Croatia", cca2: "HR", "calling-code": "385"}, {
    name: "Cuba",
    cca2: "CU",
    "calling-code": "53"
  }, {name: "Cyprus", cca2: "CY", "calling-code": "357"}, {
    name: "Czech Republic",
    cca2: "CZ",
    "calling-code": "420"
  }, {name: "Denmark", cca2: "DK", "calling-code": "45"}, {
    name: "Djibouti",
    cca2: "DJ",
    "calling-code": "253"
  }, {name: "Dominica", cca2: "DM", "calling-code": "1767"}, {
    name: "Dominican Republic",
    cca2: "DO",
    "calling-code": "1809"
  }, {name: "Ecuador", cca2: "EC", "calling-code": "593"}, {
    name: "Egypt",
    cca2: "EG",
    "calling-code": "20"
  }, {name: "El Salvador", cca2: "SV", "calling-code": "503"}, {
    name: "Equatorial Guinea",
    cca2: "GQ",
    "calling-code": "240"
  }, {name: "Eritrea", cca2: "ER", "calling-code": "291"}, {
    name: "Estonia",
    cca2: "EE",
    "calling-code": "372"
  }, {name: "Ethiopia", cca2: "ET", "calling-code": "251"}, {
    name: "Faroe Islands",
    cca2: "FO",
    "calling-code": "298"
  }, {name: "Fiji", cca2: "FJ", "calling-code": "679"}, {
    name: "Finland",
    cca2: "FI",
    "calling-code": "358"
  }, {name: "France", cca2: "FR", "calling-code": "33"}, {
    name: "French Polynesia",
    cca2: "PF",
    "calling-code": "689"
  }, {name: "Gabon", cca2: "GA", "calling-code": "241"}, {
    name: "Gambia",
    cca2: "GM",
    "calling-code": "220"
  }, {name: "Georgia", cca2: "GE", "calling-code": "995"}, {
    name: "Germany",
    cca2: "DE",
    "calling-code": "49"
  }, {name: "Ghana", cca2: "GH", "calling-code": "233"}, {
    name: "Gibraltar",
    cca2: "GI",
    "calling-code": "350"
  }, {name: "Greece", cca2: "GR", "calling-code": "30"}, {
    name: "Greenland",
    cca2: "GL",
    "calling-code": "299"
  }, {name: "Grenada", cca2: "GD", "calling-code": "1473"}, {
    name: "Guadeloupe",
    cca2: "GP",
    "calling-code": "590"
  }, {name: "Guam", cca2: "GU", "calling-code": "1671"}, {
    name: "Guatemala",
    cca2: "GT",
    "calling-code": "502"
  }, {name: "Guernsey", cca2: "GG", "calling-code": "44"}, {
    name: "Guinea",
    cca2: "GN",
    "calling-code": "224"
  }, {name: "Guinea-Bissau", cca2: "GW", "calling-code": "245"}, {
    name: "Guyana",
    cca2: "GY",
    "calling-code": "592"
  }, {name: "Haiti", cca2: "HT", "calling-code": "509"}, {
    name: "Honduras",
    cca2: "HN",
    "calling-code": "504"
  }, {name: "Hong Kong", cca2: "HK", "calling-code": "852"}, {
    name: "Hungary",
    cca2: "HU",
    "calling-code": "36"
  }, {name: "Iceland", cca2: "IS", "calling-code": "354"}, {
    name: "India",
    cca2: "IN",
    "calling-code": "91"
  }, {name: "Indonesia", cca2: "ID", "calling-code": "62"}, {
    name: "Iran",
    cca2: "IR",
    "calling-code": "98"
  }, {name: "Iraq", cca2: "IQ", "calling-code": "964"}, {
    name: "Ireland",
    cca2: "IE",
    "calling-code": "353"
  }, {name: "Isle of Man", cca2: "IM", "calling-code": "44"}, {
    name: "Israel",
    cca2: "IL",
    "calling-code": "972"
  }, {name: "Italy", cca2: "IT", "calling-code": "39"}, {
    name: "Jamaica",
    cca2: "JM",
    "calling-code": "1876"
  }, {name: "Japan", cca2: "JP", "calling-code": "81"}, {
    name: "Jersey",
    cca2: "JE",
    "calling-code": "44"
  }, {name: "Jordan", cca2: "JO", "calling-code": "962"}, {
    name: "Kazakhstan",
    cca2: "KZ",
    "calling-code": "7"
  }, {name: "Kenya", cca2: "KE", "calling-code": "254"}, {
    name: "Kiribati",
    cca2: "KI",
    "calling-code": "686"
  }, {name: "Kuwait", cca2: "KW", "calling-code": "965"}, {
    name: "Kyrgyzstan",
    cca2: "KG",
    "calling-code": "996"
  }, {name: "Laos", cca2: "LA", "calling-code": "856"}, {
    name: "Latvia",
    cca2: "LV",
    "calling-code": "371"
  }, {name: "Lebanon", cca2: "LB", "calling-code": "961"}, {
    name: "Lesotho",
    cca2: "LS",
    "calling-code": "266"
  }, {name: "Liberia", cca2: "LR", "calling-code": "231"}, {
    name: "Libya",
    cca2: "LY",
    "calling-code": "218"
  }, {name: "Liechtenstein", cca2: "LI", "calling-code": "423"}, {
    name: "Lithuania",
    cca2: "LT",
    "calling-code": "370"
  }, {name: "Luxembourg", cca2: "LU", "calling-code": "352"}, {
    name: "Macao",
    cca2: "MO",
    "calling-code": "853"
  }, {name: "Macedonia", cca2: "MK", "calling-code": "389"}, {
    name: "Madagascar",
    cca2: "MG",
    "calling-code": "261"
  }, {name: "Malawi", cca2: "MW", "calling-code": "265"}, {
    name: "Malaysia",
    cca2: "MY",
    "calling-code": "60"
  }, {name: "Maldives", cca2: "MV", "calling-code": "960"}, {
    name: "Mali",
    cca2: "ML",
    "calling-code": "223"
  }, {name: "Malta", cca2: "MT", "calling-code": "356"}, {
    name: "Marshall Islands",
    cca2: "MH",
    "calling-code": "692"
  }, {name: "Martinique", cca2: "MQ", "calling-code": "596"}, {
    name: "Mauritania",
    cca2: "MR",
    "calling-code": "222"
  }, {name: "Mauritius", cca2: "MU", "calling-code": "230"}, {
    name: "Mexico",
    cca2: "MX",
    "calling-code": "52"
  }, {name: "Micronesia", cca2: "FM", "calling-code": "691"}, {
    name: "Moldova",
    cca2: "MD",
    "calling-code": "373"
  }, {name: "Monaco", cca2: "MC", "calling-code": "377"}, {
    name: "Mongolia",
    cca2: "MN",
    "calling-code": "976"
  }, {name: "Montenegro", cca2: "ME", "calling-code": "382"}, {
    name: "Montserrat",
    cca2: "MS",
    "calling-code": "1664"
  }, {name: "Morocco", cca2: "MA", "calling-code": "212"}, {
    name: "Mozambique",
    cca2: "MZ",
    "calling-code": "258"
  }, {name: "Myanmar (Burma)", cca2: "MM", "calling-code": "95"}, {
    name: "Namibia",
    cca2: "NA",
    "calling-code": "264"
  }, {name: "Nauru", cca2: "NR", "calling-code": "674"}, {
    name: "Nepal",
    cca2: "NP",
    "calling-code": "977"
  }, {name: "Netherlands", cca2: "NL", "calling-code": "31"}, {
    name: "New Caledonia",
    cca2: "NC",
    "calling-code": "687"
  }, {name: "New Zealand", cca2: "NZ", "calling-code": "64"}, {
    name: "Nicaragua",
    cca2: "NI",
    "calling-code": "505"
  }, {name: "Niger", cca2: "NE", "calling-code": "227"}, {
    name: "Nigeria",
    cca2: "NG",
    "calling-code": "234"
  }, {name: "North Korea", cca2: "KP", "calling-code": "850"}, {
    name: "Norway",
    cca2: "NO",
    "calling-code": "47"
  }, {name: "Oman", cca2: "OM", "calling-code": "968"}, {
    name: "Pakistan",
    cca2: "PK",
    "calling-code": "92"
  }, {name: "Palau", cca2: "PW", "calling-code": "680"}, {
    name: "Palestinian Territory",
    cca2: "PS",
    "calling-code": "970"
  }, {name: "Panama", cca2: "PA", "calling-code": "507"}, {
    name: "Papua New Guinea",
    cca2: "PG",
    "calling-code": "675"
  }, {name: "Paraguay", cca2: "PY", "calling-code": "595"}, {
    name: "Peru",
    cca2: "PE",
    "calling-code": "51"
  }, {name: "Philippines", cca2: "PH", "calling-code": "63"}, {
    name: "Poland",
    cca2: "PL",
    "calling-code": "48"
  }, {name: "Portugal", cca2: "PT", "calling-code": "351"}, {
    name: "Puerto Rico",
    cca2: "PR",
    "calling-code": "1787"
  }, {name: "Qatar", cca2: "QA", "calling-code": "974"}, {
    name: "Réunion",
    cca2: "RE",
    "calling-code": "262"
  }, {name: "Romania", cca2: "RO", "calling-code": "40"}, {
    name: "Russian Federation",
    cca2: "RU",
    "calling-code": "7"
  }, {name: "Rwanda", cca2: "RW", "calling-code": "250"}, {
    name: "Saint Kitts and Nevis",
    cca2: "KN",
    "calling-code": "1869"
  }, {name: "Saint Lucia", cca2: "LC", "calling-code": "1758"}, {
    name: "Saint Vincent and the Grenadines",
    cca2: "VC",
    "calling-code": "1784"
  }, {name: "Samoa", cca2: "WS", "calling-code": "685"}, {
    name: "San Marino",
    cca2: "SM",
    "calling-code": "378"
  }, {name: "São Tomé and Príncipe", cca2: "ST", "calling-code": "239"}, {
    name: "Saudi Arabia",
    cca2: "SA",
    "calling-code": "966"
  }, {name: "Senegal", cca2: "SN", "calling-code": "221"}, {
    name: "Serbia",
    cca2: "RS",
    "calling-code": "381"
  }, {name: "Seychelles", cca2: "SC", "calling-code": "248"}, {
    name: "Sierra Leone",
    cca2: "SL",
    "calling-code": "232"
  }, {name: "Singapore", cca2: "SG", "calling-code": "65"}, {
    name: "Slovakia",
    cca2: "SK",
    "calling-code": "421"
  }, {name: "Slovenia", cca2: "SI", "calling-code": "386"}, {
    name: "Solomon Islands",
    cca2: "SB",
    "calling-code": "677"
  }, {name: "Somalia", cca2: "SO", "calling-code": "252"}, {
    name: "South Africa",
    cca2: "ZA",
    "calling-code": "27"
  }, {name: "South Korea", cca2: "KR", "calling-code": "82"}, {
    name: "Spain",
    cca2: "ES",
    "calling-code": "34"
  }, {name: "Sri Lanka", cca2: "LK", "calling-code": "94"}, {
    name: "Sudan",
    cca2: "SD",
    "calling-code": "249"
  }, {name: "Suriname", cca2: "SR", "calling-code": "597"}, {
    name: "Swaziland",
    cca2: "SZ",
    "calling-code": "268"
  }, {name: "Sweden", cca2: "SE", "calling-code": "46"}, {
    name: "Switzerland",
    cca2: "CH",
    "calling-code": "41"
  }, {name: "Syrian Arab Republic", cca2: "SY", "calling-code": "963"}, {
    name: "Taiwan, Province of China",
    cca2: "TW",
    "calling-code": "886"
  }, {name: "Tajikistan", cca2: "TJ", "calling-code": "992"}, {
    name: "Tanzania",
    cca2: "TZ",
    "calling-code": "255"
  }, {name: "Thailand", cca2: "TH", "calling-code": "66"}, {
    name: "Timor-Leste",
    cca2: "TL",
    "calling-code": "670"
  }, {name: "Togo", cca2: "TG", "calling-code": "228"}, {
    name: "Tonga",
    cca2: "TO",
    "calling-code": "676"
  }, {name: "Trinidad and Tobago", cca2: "TT", "calling-code": "1868"}, {
    name: "Tunisia",
    cca2: "TN",
    "calling-code": "216"
  }, {name: "Turkey", cca2: "TR", "calling-code": "90"}, {
    name: "Turkmenistan",
    cca2: "TM",
    "calling-code": "993"
  }, {name: "Turks and Caicos Islands", cca2: "TC", "calling-code": "1649"}, {
    name: "Tuvalu",
    cca2: "TV",
    "calling-code": "688"
  }, {name: "Uganda", cca2: "UG", "calling-code": "256"}, {
    name: "Ukraine",
    cca2: "UA",
    "calling-code": "380"
  }, {name: "United Arab Emirates", cca2: "AE", "calling-code": "971"}, {
    name: "United Kingdom",
    cca2: "GB",
    "calling-code": "44"
  }, {name: "United States", cca2: "US", "calling-code": "1"}, {
    name: "Uruguay",
    cca2: "UY",
    "calling-code": "598"
  }, {name: "Uzbekistan", cca2: "UZ", "calling-code": "998"}, {
    name: "Vanuatu",
    cca2: "VU",
    "calling-code": "678"
  }, {name: "Vatican City", cca2: "VA", "calling-code": "379"}, {
    name: "Venezuela",
    cca2: "VE",
    "calling-code": "58"
  }, {name: "Viet Nam", cca2: "VN", "calling-code": "84"}, {
    name: "Virgin Islands (British)",
    cca2: "VG",
    "calling-code": "1284"
  }, {name: "Virgin Islands (U.S.)", cca2: "VI", "calling-code": "1340"}, {
    name: "Western Sahara",
    cca2: "EH",
    "calling-code": "212"
  }, {name: "Yemen", cca2: "YE", "calling-code": "967"}, {
    name: "Zambia",
    cca2: "ZM",
    "calling-code": "260"
  }, {name: "Zimbabwe", cca2: "ZW", "calling-code": "263"}], countryCodes: {
    1: ["US"],
    7: ["RU", "KZ"],
    20: ["EG"],
    27: ["ZA"],
    30: ["GR"],
    31: ["NL"],
    32: ["BE"],
    33: ["FR"],
    34: ["ES"],
    36: ["HU"],
    39: ["IT"],
    40: ["RO"],
    41: ["CH"],
    43: ["AT"],
    44: ["GB", "GG", "IM", "JE"],
    45: ["DK"],
    46: ["SE"],
    47: ["NO", "SJ"],
    48: ["PL"],
    49: ["DE"],
    51: ["PE"],
    52: ["MX"],
    53: ["CU"],
    54: ["AR"],
    55: ["BR"],
    56: ["CL"],
    57: ["CO"],
    58: ["VE"],
    60: ["MY"],
    61: ["AU", "CC", "CX"],
    62: ["ID"],
    63: ["PH"],
    64: ["NZ"],
    65: ["SG"],
    66: ["TH"],
    81: ["JP"],
    82: ["KR"],
    84: ["VN"],
    86: ["CN"],
    90: ["TR"],
    91: ["IN"],
    92: ["PK"],
    93: ["AF"],
    94: ["LK"],
    95: ["MM"],
    98: ["IR"],
    211: ["SS"],
    212: ["MA", "EH"],
    213: ["DZ"],
    216: ["TN"],
    218: ["LY"],
    220: ["GM"],
    221: ["SN"],
    222: ["MR"],
    223: ["ML"],
    224: ["GN"],
    225: ["CI"],
    226: ["BF"],
    227: ["NE"],
    228: ["TG"],
    229: ["BJ"],
    230: ["MU"],
    231: ["LR"],
    232: ["SL"],
    233: ["GH"],
    234: ["NG"],
    235: ["TD"],
    236: ["CF"],
    237: ["CM"],
    238: ["CV"],
    239: ["ST"],
    240: ["GQ"],
    241: ["GA"],
    242: ["CG"],
    243: ["CD"],
    244: ["AO"],
    245: ["GW"],
    246: ["IO"],
    247: ["AC"],
    248: ["SC"],
    249: ["SD"],
    250: ["RW"],
    251: ["ET"],
    252: ["SO"],
    253: ["DJ"],
    254: ["KE"],
    255: ["TZ"],
    256: ["UG"],
    257: ["BI"],
    258: ["MZ"],
    260: ["ZM"],
    261: ["MG"],
    262: ["RE", "YT"],
    263: ["ZW"],
    264: ["NA"],
    265: ["MW"],
    266: ["LS"],
    267: ["BW"],
    268: ["SZ"],
    269: ["KM"],
    290: ["SH"],
    291: ["ER"],
    297: ["AW"],
    298: ["FO"],
    299: ["GL"],
    350: ["GI"],
    351: ["PT"],
    352: ["LU"],
    353: ["IE"],
    354: ["IS"],
    355: ["AL"],
    356: ["MT"],
    357: ["CY"],
    358: ["FI", "AX"],
    359: ["BG"],
    370: ["LT"],
    371: ["LV"],
    372: ["EE"],
    373: ["MD"],
    374: ["AM"],
    375: ["BY"],
    376: ["AD"],
    377: ["MC"],
    378: ["SM"],
    379: ["VA"],
    380: ["UA"],
    381: ["RS"],
    382: ["ME"],
    385: ["HR"],
    386: ["SI"],
    387: ["BA"],
    389: ["MK"],
    420: ["CZ"],
    421: ["SK"],
    423: ["LI"],
    500: ["FK"],
    501: ["BZ"],
    502: ["GT"],
    503: ["SV"],
    504: ["HN"],
    505: ["NI"],
    506: ["CR"],
    507: ["PA"],
    508: ["PM"],
    509: ["HT"],
    590: ["GP", "BL", "MF"],
    591: ["BO"],
    592: ["GY"],
    593: ["EC"],
    594: ["GF"],
    595: ["PY"],
    596: ["MQ"],
    597: ["SR"],
    598: ["UY"],
    599: ["CW", "BQ"],
    670: ["TL"],
    672: ["NF"],
    673: ["BN"],
    674: ["NR"],
    675: ["PG"],
    676: ["TO"],
    677: ["SB"],
    678: ["VU"],
    679: ["FJ"],
    680: ["PW"],
    681: ["WF"],
    682: ["CK"],
    683: ["NU"],
    685: ["WS"],
    686: ["KI"],
    687: ["NC"],
    688: ["TV"],
    689: ["PF"],
    690: ["TK"],
    691: ["FM"],
    692: ["MH"],
    850: ["KP"],
    852: ["HK"],
    853: ["MO"],
    855: ["KH"],
    856: ["LA"],
    880: ["BD"],
    886: ["TW"],
    960: ["MV"],
    961: ["LB"],
    962: ["JO"],
    963: ["SY"],
    964: ["IQ"],
    965: ["KW"],
    966: ["SA"],
    967: ["YE"],
    968: ["OM"],
    970: ["PS"],
    971: ["AE"],
    972: ["IL"],
    973: ["BH"],
    974: ["QA"],
    975: ["BT"],
    976: ["MN"],
    977: ["NP"],
    992: ["TJ"],
    993: ["TM"],
    994: ["AZ"],
    995: ["GE"],
    996: ["KG"],
    998: ["UZ"],
    1242: ["BS"],
    1246: ["BB"],
    1264: ["AI"],
    1268: ["AG"],
    1284: ["VG"],
    1340: ["VI"],
    1345: ["KY"],
    1441: ["BM"],
    1473: ["GD"],
    1649: ["TC"],
    1664: ["MS"],
    1671: ["GU"],
    1684: ["AS"],
    1758: ["LC"],
    1767: ["DM"],
    1784: ["VC"],
    1787: ["PR"],
    1809: ["DO"],
    1868: ["TT"],
    1869: ["KN"],
    1876: ["JM"]
  }
};

function mo2f_valid(f) {

  !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
}

function search(element) {
  var arr = [];

  for (let i = 0; i < intlTelInput.countries.length; i++) {
    if (element == intlTelInput.countries[i]["calling-code"]) {
      return 1;
    }
  }
  return 0;
}