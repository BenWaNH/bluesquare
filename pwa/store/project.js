import projectsService from "../services/project";
import makeCrudModule from "./crud";

export default makeCrudModule({
  service: projectsService,
});
