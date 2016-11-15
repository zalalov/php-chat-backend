### Installation
The command below will create new Docker image called 'bunq' and will run it. REST API will be available on 8000 port of your docker machine's address.
```sh
./build.sh && ./start.sh
```
For reverting all changes stop the server and run the command below:
```sh
docker rmi bunq
```

### Using
Tokens:
* Alice: `dcebfcb3a8fc94523b571ce997afa5b8`
* Bob: `a84ca8006fa758be44db7f096ae78b8e`

Get message history:
```sh
$ curl -X POST -F "token=<TOKEN>” http://<SERVER_IP>/api/message/history
```

Get new messages:
```sh
$ curl -X POST -F "token=<TOKEN>” http://<SERVER_IP>/api/message/new
```

Send message from Alice to Bob:
```sh
$ curl -X POST -F "token=dcebfcb3a8fc94523b571ce997afa5b8" -F "to_user_id=2" -F "from_user_id=1" -F "text='hello my friend'" http://<SERVER_IP>/api/message/send
```

Examples:
```sh
$ curl -X POST -F "token=dcebfcb3a8fc94523b571ce997afa5b8" -F "to_user_id=2" -F "from_user_id=1" -F "text='hello my friend'" http://192.168.99.100:8000/api/message/send
$ curl -X POST -F "token=a84ca8006fa758be44db7f096ae78b8e" http://192.168.99.100:8000/api/message/history
```
