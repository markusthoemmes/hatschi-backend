# Hatschi Backend

---

**Note:** This is not under active development by myself and has been created as a proof of concept for a clinical study. As such the inputs are currently hardcoded to the app of said study which is not open-source as the time of writing, so I can't really reference that. It's not supposed to be a general purpose backend but could potentially be adjusted to be.

---

## How to use

1. Setup a server that can interpret PHP, like [apache](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04).
2. Setup an SQL server, like [MySQL](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-ubuntu-18-04).
3. Create a database using the given table definitions (TODO: Add them).
4. Enter the database credentials into `config.php`.
5. Ship the backend using the PHP webserver.

## How to ingest data

### Cough data

Cough data is ingested into the backend using the `ingest_cough.php` file. The data needs to be `POST`ed in a JSON encoding like the following

```json
{
    "timestamp": 1, // Milliseconds since epoch
    "name": "test", // "name" of the patient (usually a pseudonym)
    "probA": 0.95,  // Probability A
    "probB": 0.95,  // Probability B
    "probC": 0.95,  // Probability C
    "probD": 0.95,  // Probability D
    "probE": 0.95,  // Probability E
    "maxAmp": 10.0  // Maximum amplitude
}
```

### Status data

Status data is used to determine the healthiness of the devices used to track patients, to be able to intervene and to have control data to undermine study results. The respective data needs to be `POST`ed in a JSON encoding to `update_status.php`.

```json
{
    "id": "foo",                  // ID of the device
    "currently_tracking": "test", // The patient being tracked
    "battery": 0.7,               // Battery charge
    "free_space": 100,            // Space left on the device
    "recording": "foo",           // Recording status of the device
    "detecting": "bar",           // Detecting status of the device
    "room": "01-123",             // Room of the device
    "uptime": "test"              // Uptime status
}
```

## Why PHP?

I usually don't do a lot with PHP (anymore) but for this project it was the perfect fit. Development time was ultra short (a few days) and the study would run 24/7. I didn't want to trust myself to write perfect software in that timeframe and thus opted for PHP which virtually can not crash or get in a weird state as the scripts are interpreted freshly each time. 