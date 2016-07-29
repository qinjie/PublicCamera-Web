__author__ = 'zqi2'

import requests
from requests.auth import HTTPBasicAuth

from entity import Entity


class FloorData(Entity):
    def __init__(self):
        self._base_url = ''
        self._urls = {}
        self._config_section = ''
        Entity.__init__(self, config_section='floor_data')

    def latest_by_project(self, projectId, auth=None):
        url = self._urls['latest_by_project'].replace("<projectId>", str(projectId))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("LATEST_BY_PROJECT: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def latest_by_project_and_label(self, projectId, label, auth=None):
        url = self._urls['latest_by_project_and_label'].replace("<projectId>", str(projectId))
        url = url.replace("<label>", str(label))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("LATEST_BY_PROJECT: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def floor_crowd_today(self, auth=None):
        url = self._urls['floor_crowd_today']
        headers = {'Accept': 'application/json'}
        r = requests.post(url, auth=auth, headers=headers)
        self._logger.info("floor_crowd_today: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def floor_crowd_weekly(self, auth=None):
        url = self._urls['floor_crowd_weekly']
        headers = {'Accept': 'application/json'}
        r = requests.post(url, auth=auth, headers=headers)
        self._logger.info("floor_crowd_weekly: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def floor_crowd_monthly(self, auth=None):
        url = self._urls['floor_crowd_monthly']
        headers = {'Accept': 'application/json'}
        r = requests.post(url, auth=auth, headers=headers)
        self._logger.info("floor_crowd_monthly: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def floor_crowd_weekdays(self, auth=None):
        url = self._urls['floor_crowd_weekdays']
        headers = {'Accept': 'application/json'}
        r = requests.post(url, auth=auth, headers=headers)
        self._logger.info("floor_crowd_weekdays: %s", url)
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

    # Username and Password for Authentication
    username = 'user1'
    password = '123456'
    auth = HTTPBasicAuth(username, password)

    entity = FloorData()

    # LIST
    entity.list(auth)

    # VIEW
    entity.view(2)

    # SEARCH
    entity.search('label=CrowdIndex&floorId=1', auth)

    # LATEST_BY_PROJECT
    entity.latest_by_project(projectId=1, auth=auth)

    # LATEST_BY_PROJECT_AND_LABEL
    label = "CrowdIndex"
    entity.latest_by_project_and_label(projectId=1, label=label, auth=auth)

    # CREATE
    data = {'floorId': '1', 'label': 'CrowdIndex', 'value': '60'}
    r = entity.create(data, auth)

    if r.status_code == 201:
        # UPDATE
        obj = r.json()
        obj['label'] = 'CrowdIndex'
        obj['value'] = '22'
        r2 = entity.update(obj, auth)

        # DELETE
        r3 = entity.delete(obj['id'], auth)

    entity.floor_crowd_today()
    entity.floor_crowd_weekly()
    entity.floor_crowd_monthly()
    entity.floor_crowd_weekdays()

