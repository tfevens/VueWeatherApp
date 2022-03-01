<template>
    <div class="w-128 max-w-lg">
        <div id="geocoder"></div>
        <div class="text-white mb-8">
                <div class="weather-container font-sans rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-4">
                    <div class="current-weather flex items-center justify-between px-6 py-8">
                        <div class="flex items-center">
                            <div>
                                <div class="text-6xl font-semibold">{{currentTemp.actual}}°C</div>
                                <div>Feel like {{currentTemp.feelsLike}}°C</div>
                            </div>
                            <div class="mx-5">
                                <div class="font-semibold">
                                    {{currentTemp.summary}}
                                </div>
                                <div>{{location.name}}</div>
                            </div>
                        </div>
                        <div>
                            <canvas id="iconCurrent" width="96" height="96"></canvas>
                        </div>
                    </div><!-- end current-weather -->
                    <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">
                        <div v-for="(day, index) in forecast"
                            :key="day.dt"
                            class="flex items-center"
                            :class="{'mt-8' : index > 0 }"
                            >
                            <div class="w-1/6 text-lg text-gray-200">{{toDayOfWeek(day.dt)}}</div>
                            <div class="w-4/6 px-4 flex items-center">
                                <div><canvas :id="`icon${index+1}`" :data-icon="getWeatherIcon(day.weather[0].icon)"  width="24" height="24"></canvas></div>
                                <div class="ml-3">{{day.weather[0].main}}</div>
                            </div>
                            <div class="w-1/6 text-right">
                                <div>{{ Math.round(day.temp.max) }}°C</div>
                                <div>{{ Math.round(day.temp.min) }}°C</div>
                            </div>
                        </div>
                    </div><!-- end future-weather -->
                </div> <!-- end weather-container -->
            </div>
    </div>
</template>

<script>
export default {
    mounted() {            
        this.fetchData()

        mapboxgl.accessToken = 'pk.eyJ1IjoidGZldmVucyIsImEiOiJjbDA2cTlzcnowZGZmM3JydXhjaHNuYTgxIn0.KQ_XHonI_WbSAs6aK_ZcoA';
            const geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                types: 'place'
            });
            
            geocoder.addTo('#geocoder');
            
            // Add geocoder result to container.
            geocoder.on('result', (e) => {
                this.location.name = e.result.place_name
                this.location.lat = e.result.center[1]
                this.location.lon = e.result.center[0]
            });
    },
    watch: {
        location: {
            handler() {
                this.fetchData()
            },
            deep: true
        }
    },
    data() {
        return {
            location: {
                name: 'Halifax, Nova Scotia, Canada',
                lat: '44.6486',
                lon: '-63.5859'
            },

            daily:[],

            currentTemp: {
                actual: '',
                feelsLike: '',
                summary: '',
                icon: '',
            }
        }
    },

    computed: {
        forecast() {
            return this.daily.slice(1,6)
        }
    },

    methods: {
        fetchData() {
            var skycons = new Skycons({'color': 'white'});

            fetch(`/api/weather?lat=${this.location.lat}&lon=${this.location.lon}`)
                .then(response => response.json())
                .then(data => {
                    this.currentTemp.actual = Math.round(data.current.temp),
                    this.currentTemp.feelsLike = Math.round(data.current.feels_like),
                    this.currentTemp.summary = data.current.weather[0].main,
                    this.currentTemp.icon = data.current.weather[0].icon

                    this.daily = data.daily

                    skycons.add(iconCurrent, this.getWeatherIcon(data.current.weather[0].icon))
                    skycons.play()

                    this.$nextTick(() => {
                        skycons.add('icon1', document.getElementById('icon1').getAttribute('data-icon'))
                        skycons.add('icon2', document.getElementById('icon2').getAttribute('data-icon'))
                        skycons.add('icon3', document.getElementById('icon3').getAttribute('data-icon'))
                        skycons.add('icon4', document.getElementById('icon4').getAttribute('data-icon'))
                        skycons.add('icon5', document.getElementById('icon5').getAttribute('data-icon'))
                        
                        skycons.play()
                    })

                })
        },
        toDayOfWeek(timestamp) {
            const newDate = new Date(timestamp*1000)
            const days = ['SUN','MON','TUE','WED','THU','FRI','SAT']

            return days[newDate.getDay()]
        },
        getWeatherIcon(icon) {
            let iconText = ''

            switch (icon) {
                case '01d':
                    iconText = 'clear-day'
                    break
                case '01n':
                    iconText = 'clear-night'
                    break

                case '02d':
                case '03d':
                    iconText = 'partly-cloudy-day'
                    break

                case '02n':
                case '03n':
                    iconText = 'partly-cloudy-night'
                    break

                case '04d':
                case '04n':
                    iconText ='cloudy'
                    break

                case '09d':
                    iconText = 'showers-day'
                    break                    
                case '09n':
                    iconText = 'showers-night'
                    break

                case '10d':
                case '10n':
                    iconText = 'rain'
                    break

                case '11d':
                case '11n':
                    iconText = 'thunder'
                    break

                case '13d':
                    iconText = 'snow-showers-day'
                    break
                case '13n':
                    iconText = 'snow-showers-night'
                    break

                case '50d':
                case '50n':
                    iconText = 'fog'
                    break

                default:
                    console.log(icon)
                    iconText = 'wind'
            }
            return iconText
        }
    }
}
</script>

<style>
#geocoder {z-index: 1}
.mapboxgl-ctrl-geocoder {min-width: 100%}
</style>