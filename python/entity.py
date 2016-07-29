import ConfigParser
import json
from urlparse import urlparse

import requests
from requests.auth import HTTPBasicAuth

from utils import init_logger


class Entity:
    # configuration file and section name
    _config_file = 'server.ini'

    def __init__(self, config_section='default'):
        self._logger = init_logger()
        self._base_url = ''
        self._urls = {}
        self._config_section = config_section
        # web service URLs
        self.read_config(self._config_section)

    # Read settings from config file
    def read_config(self, section):

        parser = ConfigParser.SafeConfigParser()
        parser.read(self._config_file)
        parser.defaults()
        self._base_url = parser.get('default', 'url_base')

        self._logger.info('Reading urls from setting file')
        for key, val in parser.items(section):
            self._urls[key] = self._base_url + parser.get(section, key)

        # validate URLs
        for _, url in self._urls.iteritems():
            parsed_url = urlparse(url)
            if not bool(parsed_url.scheme):
                self._logger.error('Invalid URL: ' + url)
            else:
                # self.log.info('URL OK: ' + url)
                pass

    def list(self, auth=None):
        try:
            url = self._urls['list']
            headers = {'Accept': 'application/json'}
            r = requests.get(url, auth=auth, headers=headers)
            self._logger.info("list %s", url)
            self._logger.info("%s %s", r.status_code, r.headers['content-type'])
            self._logger.info(r.text)
            return r
        except requests.exceptions.RequestException as e:
            self._logger.error("Exception: " + str(e.message))
            return None

    def view(self, data_id, auth=None):
        try:
            url = self._urls['view'].replace("<id>", str(data_id))
            headers = {'Accept': 'application/json'}
            r = requests.get(url, auth=auth, headers=headers)
            self._logger.info("view: %s", url)
            self._logger.info("%s %s", r.status_code, r.headers['content-type'])
            self._logger.info(r.text)
            return r
        except requests.exceptions.RequestException as e:
            self._logger.error("Exception: " + str(e.message))
            return None

    def search(self, query, auth=None):
        try:
            url = self._urls['search'].replace("<query>", str(query))
            headers = {'Accept': 'application/json'}
            r = requests.get(url, auth=auth, headers=headers)
            self._logger.info("search: %s", url)
            self._logger.info("%s %s", r.status_code, r.headers['content-type'])
            self._logger.info(r.text)
            return r
        except requests.exceptions.RequestException as e:
            self._logger.error("Exception: " + str(e.message))
            return None

    def create(self, payload, auth=None):
        try:
            url = self._urls['create']
            data = json.dumps(payload)
            headers = {'Content-Type': 'application/json', 'Accept': 'application/json'}
            r = requests.post(url, auth=auth, data=data, headers=headers)
            self._logger.info("create: %s", url)
            self._logger.info("Payload = %s", data)
            self._logger.info("%s %s", r.status_code, r.headers['content-type'])
            self._logger.info(r.text)
            return r
        except requests.exceptions.RequestException as e:
            self._logger.error("Exception: " + str(e.message))
            return None

    def update(self, payload, auth=None):
        try:
            url = self._urls['update']
            id = payload['id']
            url = url.replace("<id>", str(id))
            # if 'id' in payload: del payload['id']

            data = json.dumps(payload)
            headers = {'Content-Type': 'application/json', 'Accept': 'application/json'}
            r = requests.put(url, data=data, auth=auth, headers=headers)
            self._logger.info("update: %s", url)
            self._logger.info("Payload = %s", data)
            self._logger.info("%s %s", r.status_code, r.headers['content-type'])
            self._logger.info(r.text)
            return r
        except requests.exceptions.RequestException as e:
            self._logger.error("Exception: " + str(e.message))
            return None

    def delete(self, data_id, auth=None):
        try:
            url = self._urls['delete']
            url = url.replace("<id>", str(data_id))
            r = requests.delete(url, auth=auth)
            self._logger.info("delete: %s", url)
            self._logger.info("%s %s", r.status_code, r.headers['content-type'])
            self._logger.info(r.text)
            return r
        except requests.exceptions.RequestException as e:
            self._logger.error("Exception: " + str(e.message))
            return None


if __name__ == '__main__':

    # # Read options from command line
    # argParser = argparse.ArgumentParser('API Entity')
    # argParser.add_argument('-c', '--configFile', help="Configuration file", required=False)
    # argParser.add_argument('-s', '--configSession', help="Configuration session", required=False)
    # argParser.add_argument('-u', '--username', help="Username", required=False)
    # argParser.add_argument('-p', '--password', help="Password", required=False)
    # args = argParser.parse_args()

    # Username and Password for Authentication
    username = 'user1'
    password = '123456'
    auth = HTTPBasicAuth(username, password)

    entity = Entity()

    # LIST
    entity.list(auth)

    # VIEW
    entity.view(4)

    # SEARCH
    entity.search('code=CN', auth)

    # CREATE
    data = {'code': 'CD', 'name': 'cdcdcd', 'population': '223344'}
    r = entity.create(data, auth)

    if r.status_code == 201:
        # UPDATE
        obj = r.json()
        obj['name'] = 'cd2cd2cd2'
        obj['population'] = '222333'
        r2 = entity.update(obj, auth)

        # DELETE
        r3 = entity.delete(obj['id'], auth)
