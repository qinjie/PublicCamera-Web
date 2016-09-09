__author__ = 'zqi2'

import os
import utils
import requests
from requests.auth import HTTPBasicAuth

from entity import Entity


class NodeFile(Entity):
    def __init__(self):
        self._base_url = ''
        self._urls = {}
        self._config_section = ''

        Entity.__init__(self, config_section='node_file')

    def upload(self, payload, auth=None):

        # -- Check if fileName is set
        if ('fileName' not in payload):
            self._logger.error("fileName or fileType not set in payload.")
            return False
        # -- Check if file exists
        if not os.path.isfile(payload['fileName']):
            self._logger.error("File not found: %s", payload['fileName'])
            return False
        # -- Set the 'file' form-data
        if 'fileType' in payload:
            files = {'file': (payload['fileName'], open(payload['fileName'], 'rb'), payload['fileType'])}
        else:
            files = {'file': (payload['fileName'], open(payload['fileName'], 'rb'))}

        url = self._urls['upload']
        headers = {'Accept': 'application/json'}
        r = requests.post(url, files=files, data=payload, auth=auth, headers=headers)
        self._logger.info("upload: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def latest_by_project(self, projectId, auth=None):

        url = self._urls['latest_by_project'].replace("<projectId>", str(projectId))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("latest_by_project: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def latest_by_project_and_label(self, projectId, label, auth=None):

        url = self._urls['latest_by_project_and_label'].replace("<projectId>", str(projectId))
        url = url.replace("<label>", str(label))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("latest_by_project_and_label: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def delete_hours_older(self, hours, auth=None):

        url = self._urls['delete_hours_older'].replace("<hours>", str(hours))
        headers = {'Accept': 'application/json'}
        r = requests.delete(url, auth=auth, headers=headers)
        self._logger.info("delete_hours_older: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r


    def keep_latest_n_each(self, cnt=720, auth=None):

        url = self._urls['keep_latest_n_each'].replace("<cnt>", str(cnt))
        headers = {'Accept': 'application/json'}
        r = requests.delete(url, auth=auth, headers=headers)
        self._logger.info("keep_latest_n_each: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def book_one(self, auth=None):
        url = self._urls['book_one']
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("book_one: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r


    def old_data_id_list(self, older_than_n=720, auth=None):
        url = self._urls['old_data_id_list'].replace("<older_than_n>", str(older_than_n))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("old_data_id_list: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def delete_older_than_n(self, keepn=500, auth=None):
        url = self._urls['delete_older_than_n'].replace("<keepn>", str(keepn))
        headers = {'Accept': 'application/json'}
        r = requests.delete(url, auth=auth, headers=headers)
        self._logger.info("delete_older_than_n: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r


if __name__ == '__main__':

    # # Read options from command line
    # argParser = argparse.ArgumentParser('Public Camera: API Entity')
    # argParser.add_argument('-c', '--configFile', help="Configuration file", required=False)
    # argParser.add_argument('-s', '--configSession', help="Configuration session", required=False)
    # argParser.add_argument('-u', '--username', help="Username", required=False)
    # argParser.add_argument('-p', '--password', help="Password", required=False)
    # args = argParser.parse_args()

    logger = utils.init_logger("batch")

    # Username and Password for Authentication
    username = 'user1'
    password = '123456'
    auth = HTTPBasicAuth(username, password)

    entity = NodeFile()

    # LIST
    entity.list(auth)

    # VIEW
    entity.view(4)

    # SEARCH
    entity.search('nodeId=3', auth)

    # LATEST_BY_PROJECT
    entity.latest_by_project(projectId=1, auth=auth)

    username = 'manager1'
    password = '123456'
    auth = HTTPBasicAuth(username, password)

    # LATEST_BY_PROJECT_AND_LABEL
    label = "This is your label"
    entity.latest_by_project_and_label(projectId=1, label=label, auth=auth)
    
    entity.keep_latest_n_each(cnt=500, auth=auth)

    entity.delete_older_than_n(keepn=500, auth=auth)

    # UPLOAD
    #payload = {'nodeId': 2, 'fileName': 'testfiles/0002_20160119_000911_81238700.jpg', 'label': 'This is Test 4'}

    #r = entity.upload(payload, auth)
    #if r and r.status_code == 200:
    #   obj = r.json()

        # DELETE
        # r3 = entity.delete(obj['id'], auth)

        # DELETE_HOURS_OLDER

        # DELETE_HOURS_OLDER (Only manager or admin Allowed)

# username = 'manager1'
#    password = '123456'
#    manager = HTTPBasicAuth(username, password)
#    entity.delete_hours_older(hours=48, auth=manager)
