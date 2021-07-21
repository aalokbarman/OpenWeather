/**
 *  * Copyright © Magento, Inc. All rights reserved.
 *   * See COPYING.txt for license details.
 *    */

define([
    'uiComponent',
    'jquery',
    'ko',
    'html2pdf.bundle',
], function (Component, $, ko, html2pdf) {
    'use strict';
    var self;
    return Component.extend({
        /** @inheritdoc */
        queryType: ko.observable(),
        cityNotFound: ko.observable(),
        cityWeather: ko.observable([]),
        cityWeatherHistory: ko.observable([]),
        initialize: function () {
            self = this;
            this._super();
        },
        weatherSearch: function (formElement) {
            $('#city-weather').mage(
                'validation',
                {
                    submitHandler: function (form) {
                        $.ajax({
                            url: BASE_URL + "weather/city/history",
                            data: $('#city-weather').serialize(),
                            type: 'POST',
                            dataType: 'json',
                            beforeSend: function () {
                                // show some loading icon
                                $("body").trigger("processStart");
                            },
                            success: function (data, status, xhr) {
                                $("body").trigger("processStop");

                                if (typeof data.current !== "undefined") {
                                    self.cityWeather(data.current);
                                }

                                if (typeof data.history !== "undefined") {
                                    self.cityWeatherHistory(data.history);
                                    if(data.history.length == 0){
                                        self.cityNotFound(true);
                                    }
                                    else{
                                        self.cityNotFound(false);
                                    }
                                }
                            },
                            error: function (xhr, status, errorThrown) {
                                $("body").trigger("processStop");
                                console.log('Error happens. Try again.');
                                console.log(errorThrown);
                            }
                        });
                    }
                }
            );
            return false;
        },
        downloadPdf: function () {

            var element = document.getElementById('weather-history');
            var opt = {
                margin: 1,
                filename: $("#city").val() + '-weather.pdf',
                jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
            };

            html2pdf().set(opt).from(element).save();
        }

    });
});
