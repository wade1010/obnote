kafka-topics --list --bootstrap-server 10.0.11.101:9092

kafka-topics --create --bootstrap-server 10.0.11.101:9092 --topic cxh

kafka-console-producer --bootstrap-server 10.0.11.101:9092 --topic cxh

kafka-console-consumer --bootstrap-server 10.0.11.101:9092 --topic cxh --from-beginning