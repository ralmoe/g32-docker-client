# G32 dockerized client

The [OttoWilde G32 Connected](https://www.ottowildegrillers.com/products/g32-connected-gasgrill?variant=39705156092066) 
has 4 internal temperature-sensors and 4 plug-in thermometers that send their data to the Otto Wilde Cloud. The
companion-app can pull this data and visualize it.

This docker-compose-application pulls the data from the Otto Wilde Cloud and saves it to a csv-file.

## Thanks to

* [fschwarz86](https://github.com/fschwarz86) reverse engineered the Otto Wilde api and how to setup the 
socket-connection
* [Sanderson](https://www.grillsportverein.de/forum/members/sandorson.162581/) discovered how to interpret the the 
data received from the socket-connection

## Preconditions

* The G32 needs an active internet-connection via wlan
* existing account for the otto-wilde-app
  * [Apple](https://apps.apple.com/de/app/otto-wilde-app/id1515540095)
  * [Android](https://play.google.com/store/apps/details?id=com.otto.application&hl=de&gl=US&pli=1)
* serial-number of the G32
  * find the number printed on a plate on the back of your G32 (not the sticker in your grease-drawer!)

## Usage
* rename `.env.dist` to `.env` and add 
  * app-credentials
    * username
    * password
  * G32 serial-number
  * path to output-directory
* start `docker compose up -d`

## Output-format
By default the file is stored in `/data/temperatures.csv` and looks like this:

```
1717409973;22;22;22;22;;;;
1717409975;22;22;22;22;;;;
1717409977;22;22;22;22;;;;
1717409979;22;22;22;22;;;;
1717409981;22;22;22;22;;;;
```

The grill is off, so the 4 grill zone sensors just show current the outside temperature. The thermometers are not plugged in, so the 
last four columns are empty

## Description

### Endpoints
* login: `https://mobile-api.ottowildeapp.com/login`
* grilldata: `https://mobile-api.ottowildeapp.com/v2/grills`
* socket: `socket.ottowildeapp.com` port: `4502`

## Basic workflow

* `POST`-request to login-endpoint
  * post-data: `email` and `password`  
  * response: 
    * `accessToken` (needed for following request and socket-connection)
    * general information about the user (name, birthdate, socialmedia-account if given)
* `GET`-request to grilldata-endpoint
  * httpheader: `authorization: <accessToken>` (from above request)
  * response
    * `popKey` (needed for socket-connection)
    * gasbuddy-data
    * grill-settings
    * timers
    * for an example see [docs/vs_grills.md]()
  * Socket connection 
    * start connection by sending: `{"channel":"LISTEN_TO_GRILL","data":{"grillSerialNumber":"[SERIAL-NUMBER]","pop":"[POP-KEY]"}}`
    * result: binary data, that can be decoded to temperatures
* storing the docoded information into the output file


## Unhandled issues
* If the grill is turned off, no data is received. The same happens, if the serial-number is wrong. 
This can be misleading.
* if the grill is turned off the socket uses more and more cpu and needs to be restarted

## Possible usages
* make the output-file available outside of the container and use it e.g. in [Home Assistant](https://www.home-assistant.io/)

## Roadmap
* get the filling level of the gas bottle
* get the opening state of the hood (not sure if this data is send)
* find a way to avoid using the Otto Wilde cloud by receiving the data directly from the grill.
