FROM base_alpine:latest

RUN apk --no-cache --update add bash
# Update Alpine environment
RUN apk add --update --no-cache ca-certificates openjdk8 python

## Establish Cassandra Environmental Variables
ARG CASSANDRA_VERSION
ARG CASSANDRA_HOME
ENV CASSANDRA_VERSION $CASSANDRA_VERSION
ENV CASSANDRA_HOME $CASSANDRA_HOME

## Install Cassandra
RUN wget --output-document - http://ftp.riken.jp/net/apache/cassandra/${CASSANDRA_VERSION}/apache-cassandra-${CASSANDRA_VERSION}-bin.tar.gz | tar zxvf - && \
  mv apache-cassandra-$CASSANDRA_VERSION $CASSANDRA_HOME
RUN mkdir /var/lib/cassandra /var/log/cassandra
ENV PATH $PATH:$CASSANDRA_HOME/bin

## 7000: intra-node communication
## 7001: TLS intra-node communication
## 7199: JMX
## 9042: CQL
## 9160: thrift service

RUN set -x ; \
  addgroup -g 1000 -S cassandra ; \
  adduser -u 1000 -D -S -G cassandra cassandra

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN ln -s /usr/local/bin/docker-entrypoint.sh /docker-entrypoint.sh # backwards compat
ENTRYPOINT ["docker-entrypoint.sh"]

EXPOSE 7000 7001 7199 9042 9160

CMD [ "cassandra", "-Rf" ]