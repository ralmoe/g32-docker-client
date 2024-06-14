# Return of endpoint /v2/grills

Important keys:
* popKey: The socket-request needs the popId to function
* gasbuddyInfo: information about the filling-level of the gas-bottle
* grill-settings: settings of the new grill-guide in the official companion-app
* timers: list of timers configured in the official companion-app

```
{
  "data": [
    {
      "grillBackendId": [BACKEND-ID],
      "serialNumber": "[SERIAL-NUMBER]",
      "displayInDashboard": true,
      "nickname": "[NICKNAME]",
      "preferredConnectionProtocol": "wifi",
      "isWifiConnected": true,
      "firmwareVersionCode": 11,
      "firmwareSemanticVersion": "1.1.8",
      "popKey": "[POP-KEY]",
      "gasbuddyInfo": {
        "id": [GAS-BUDDY-ID],
        "gasCapacity": 11,
        "tareWeight": 10.2,
        "criticalLevel": 10,
        "gasLevelCalibration": 0,
        "tankInstalledDate": "2024-05-24T17:20:01.000Z",
        "tsGasConsumed": "2024-06-08T19:13:33.000Z",
        "tsLastModified": "2024-06-14T17:38:17.000Z"
      },
      "bluetoothConnectionInfo": {
        "bluetoothName": "OWG-G32C-00000000",
        "bluetoothId": null
      },
      "tsLastChanged": "2024-06-14T17:38:18.000Z",
      "stscDeviceId": "fb000000",
      "grillSettings": {
        "areNotificationsEnabled": true,
        "targetTemperatures": {
          "probe1": {
            "isLocked": false,
            "targetTemperature": null
          },
          "probe2": {
            "isLocked": false,
            "targetTemperature": null
          },
          "probe3": {
            "isLocked": false,
            "targetTemperature": null
          },
          "probe4": {
            "isLocked": false,
            "targetTemperature": null
          },
          "zone1": {
            "isLocked": false,
            "targetTemperature": null,
            "isDisabled": false,
            "isTargetTempDisabled": false
          },
          "zone2": {
            "isLocked": false,
            "targetTemperature": null,
            "isDisabled": false,
            "isTargetTempDisabled": false
          },
          "zone3": {
            "isLocked": false,
            "targetTemperature": null,
            "isDisabled": false,
            "isTargetTempDisabled": false
          },
          "zone4": {
            "isLocked": false,
            "targetTemperature": null,
            "isDisabled": false,
            "isTargetTempDisabled": false
          }
        },
        "cookingType": "direct",
        "tsLastChanged": "2024-06-14T17:38:18.000Z"
      },
      "timers": [
        {
          "uuid": "bd2334ef-0559-4960-a69c-000000000000",
          "backendId": 0,
          "description": "Standard",
          "durationInSeconds": 120,
          "groupIdentifier": null,
          "isManaged": false,
          "isRepeated": false,
          "notificationsEnabled": true,
          "resetOnDone": true,
          "serialNumber": "fb000000",
          "showOnTheDashboard": true,
          "sound": "airy",
          "startTime": null,
          "secondsLeft": null,
          "lastModified": "2024-03-25T18:24:12.000Z"
        }
      ]
    }
  ]
}
```